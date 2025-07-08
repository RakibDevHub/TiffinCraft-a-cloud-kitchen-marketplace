<?php
namespace App\Models;

use Exception;

class Menu
{

    private const GET_MENU_ITEM = "
    SELECT 
        mi.*,
        (
            SELECT LISTAGG(sa.NAME, ', ') WITHIN GROUP (ORDER BY sa.NAME)
            FROM KITCHEN_SERVICE_AREAS ksa
            JOIN SERVICE_AREAS sa ON ksa.AREA_ID = sa.AREA_ID
            WHERE ksa.KITCHEN_ID = mi.KITCHEN_ID
        ) AS SERVICE_AREAS
    FROM 
        MENU_ITEMS mi
    WHERE 
        mi.AVAILABLE = 1
    ORDER BY 
        mi.NAME";

    private const GET_KITCHEN_ID_BY_OWNER = "SELECT KITCHEN_ID FROM KITCHENS WHERE OWNER_ID = :owner_id";

    private const GET_ITEMS_BY_KITCHEN_ID = "
        SELECT 
            mi.*,
            (
                SELECT ROUND(AVG(r.rating), 1)
                FROM item_reviews r
                WHERE r.item_id = mi.item_id
            ) AS avg_rating,
            (
                SELECT COUNT(*)
                FROM item_reviews r
                WHERE r.item_id = mi.item_id
            ) AS review_count
        FROM menu_items mi
        WHERE mi.kitchen_id = :kitchen_id
        AND mi.available = 1
        ORDER BY mi.created_at DESC
        ";

    private const GET_ITEM_BY_ID = "SELECT * FROM MENU_ITEMS WHERE ITEM_ID = :item_id AND KITCHEN_ID = :kitchen_id";


    private const CREATE_ITEM = "INSERT INTO MENU_ITEMS (
        KITCHEN_ID, CATEGORY_ID, NAME, DESCRIPTION, PRICE, ITEM_IMAGE, AVAILABLE, TAGS
    ) VALUES (
        :kitchen_id, :category_id, :name, :description, :price, :item_image, :available, :tags
    ) RETURNING ITEM_ID INTO :item_id";

    private const UPDATE_ITEM = "UPDATE MENU_ITEMS SET 
        CATEGORY_ID = :category_id, 
        NAME = :name, 
        DESCRIPTION = :description, 
        PRICE = :price, 
        ITEM_IMAGE = :item_image, 
        AVAILABLE = :available, 
        TAGS = :tags 
        WHERE ITEM_ID = :item_id AND KITCHEN_ID = :kitchen_id";

    private const DELETE_ITEM = "DELETE FROM MENU_ITEMS WHERE ITEM_ID = :item_id AND KITCHEN_ID = :kitchen_id";

    private const CHECK_OWNERSHIP = "SELECT 1 FROM MENU_ITEMS i 
        JOIN KITCHENS k ON i.KITCHEN_ID = k.KITCHEN_ID 
        WHERE i.ITEM_ID = :item_id AND k.OWNER_ID = :owner_id";

    private const GET_AVAILABILITY = "SELECT AVAILABLE FROM MENU_ITEMS WHERE ITEM_ID = :item_id";


    public static function getFilteredMenuItems($conn, $filters = [])
    {
        $innerQuery = "
        SELECT 
            mi.*, 
            (
                SELECT LISTAGG(sa.NAME, ', ') WITHIN GROUP (ORDER BY sa.NAME)
                FROM KITCHEN_SERVICE_AREAS ksa
                JOIN SERVICE_AREAS sa ON ksa.AREA_ID = sa.AREA_ID
                WHERE ksa.KITCHEN_ID = mi.KITCHEN_ID
            ) AS service_areas,
            (
                SELECT ROUND(AVG(r.RATING), 1)
                FROM ITEM_REVIEWS r
                WHERE r.ITEM_ID = mi.ITEM_ID
            ) AS avg_rating,
            (
                SELECT COUNT(*)
                FROM ITEM_REVIEWS r
                WHERE r.ITEM_ID = mi.ITEM_ID
            ) AS review_count,
            ROWNUM AS rn
        FROM MENU_ITEMS mi
        JOIN CATEGORIES c ON mi.CATEGORY_ID = c.CATEGORY_ID
        WHERE mi.AVAILABLE = 1
    ";

        $bindings = [];

        // Apply filters in the INNER query
        if (!empty($filters['category'])) {
            $innerQuery .= " AND LOWER(c.NAME) = :category_name";
            $bindings[':category_name'] = strtolower($filters['category']);
        }

        if (!empty($filters['search'])) {
            $innerQuery .= " AND (
            LOWER(mi.name) LIKE '%' || :search || '%' 
            OR LOWER(mi.description) LIKE '%' || :search || '%' 
            OR LOWER(mi.tags) LIKE '%' || :search || '%')";
            $bindings[':search'] = strtolower($filters['search']);
        }

        if (!empty($filters['location'])) {
            $innerQuery .= " AND EXISTS (
            SELECT 1 FROM KITCHEN_SERVICE_AREAS ksa
            JOIN SERVICE_AREAS sa ON ksa.AREA_ID = sa.AREA_ID
            WHERE ksa.KITCHEN_ID = mi.KITCHEN_ID
            AND LOWER(sa.NAME) = :location
        )";
            $bindings[':location'] = strtolower($filters['location']);
        }

        // Sorting
        if (!empty($filters['price_sort']) && $filters['price_sort'] === 'high_to_low') {
            $innerQuery .= " ORDER BY mi.price DESC";
        } else {
            $innerQuery .= " ORDER BY mi.price ASC";
        }

        // Wrap it for pagination
        $query = "SELECT * FROM (
        $innerQuery
    ) subquery WHERE rn > :offset AND rn <= :limit";

        $bindings[':offset'] = ($filters['page'] - 1) * $filters['per_page'];
        $bindings[':limit'] = $filters['page'] * $filters['per_page'];

        $stmt = oci_parse($conn, $query);
        foreach ($bindings as $param => $value) {
            oci_bind_by_name($stmt, $param, $bindings[$param]);
        }

        if (!oci_execute($stmt)) {
            throw new Exception("Execute error: " . oci_error($stmt)['message']);
        }

        $items = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $items[] = self::processData($row);
        }

        oci_free_statement($stmt);
        return $items;
    }

    public static function getTotalFilteredCount($conn, $filters = [])
    {
        $query = "
        SELECT COUNT(*) AS total
        FROM MENU_ITEMS mi
        JOIN CATEGORIES c ON mi.CATEGORY_ID = c.CATEGORY_ID
        WHERE mi.AVAILABLE = 1
    ";

        $bindings = [];

        if (!empty($filters['category'])) {
            $query .= " AND LOWER(c.NAME) = :category_name";
            $bindings[':category_name'] = strtolower($filters['category']);
        }

        if (!empty($filters['search'])) {
            $query .= " AND (LOWER(mi.name) LIKE '%' || :search || '%' 
                    OR LOWER(mi.description) LIKE '%' || :search || '%' 
                    OR LOWER(mi.tags) LIKE '%' || :search || '%')";
            $bindings[':search'] = strtolower($filters['search']);
        }

        if (!empty($filters['location'])) {
            $query .= " AND EXISTS (
                        SELECT 1 FROM KITCHEN_SERVICE_AREAS ksa
                        JOIN SERVICE_AREAS sa ON ksa.AREA_ID = sa.AREA_ID
                        WHERE ksa.KITCHEN_ID = mi.KITCHEN_ID
                        AND LOWER(sa.NAME) = :location
                    )";
            $bindings[':location'] = strtolower($filters['location']);
        }

        $stmt = oci_parse($conn, $query);
        foreach ($bindings as $param => $value) {
            oci_bind_by_name($stmt, $param, $bindings[$param]);
        }

        if (!oci_execute($stmt)) {
            throw new Exception("Execute error: " . oci_error($stmt)['message']);
        }

        $row = oci_fetch_assoc($stmt);
        oci_free_statement($stmt);

        return (int) $row['TOTAL'];
    }

    public static function getFilteredItemsForOwner($conn, $filters = [])
    {
        $innerQuery = "
            SELECT 
                mi.*, 
                (
                    SELECT LISTAGG(sa.NAME, ', ') WITHIN GROUP (ORDER BY sa.NAME)
                    FROM KITCHEN_SERVICE_AREAS ksa
                    JOIN SERVICE_AREAS sa ON ksa.AREA_ID = sa.AREA_ID
                    WHERE ksa.KITCHEN_ID = mi.KITCHEN_ID
                ) AS service_areas,
                (
                    SELECT ROUND(AVG(r.RATING), 1)
                    FROM ITEM_REVIEWS r
                    WHERE r.ITEM_ID = mi.ITEM_ID
                ) AS avg_rating,
                (
                    SELECT COUNT(*)
                    FROM ITEM_REVIEWS r
                    WHERE r.ITEM_ID = mi.ITEM_ID
                ) AS review_count,
                ROWNUM AS rn
            FROM MENU_ITEMS mi
            JOIN KITCHENS k ON mi.KITCHEN_ID = k.KITCHEN_ID
            JOIN CATEGORIES c ON mi.CATEGORY_ID = c.CATEGORY_ID
            WHERE k.OWNER_ID = :owner_id
            ";

        $bindings = [':owner_id' => $filters['owner_id']];

        // Apply filters in the INNER query
        if (!empty($filters['category'])) {
            $innerQuery .= " AND LOWER(c.NAME) = :category_name";
            $bindings[':category_name'] = strtolower($filters['category']);
        }

        if (!empty($filters['search'])) {
            $innerQuery .= " AND (
            LOWER(mi.name) LIKE '%' || :search || '%' 
            OR LOWER(mi.description) LIKE '%' || :search || '%' 
            OR LOWER(mi.tags) LIKE '%' || :search || '%')";
            $bindings[':search'] = strtolower($filters['search']);
        }

        // Status filter (available = public/private)
        if ($filters['status'] === 'public') {
            $innerQuery .= " AND mi.AVAILABLE = 1";
        } elseif ($filters['status'] === 'private') {
            $innerQuery .= " AND mi.AVAILABLE = 0";
        }

        // Default sorting by creation date (newest first)
        $innerQuery .= " ORDER BY mi.CREATED_AT DESC";

        // Wrap it for pagination
        $query = "SELECT * FROM (
        $innerQuery
    ) subquery WHERE rn > :offset AND rn <= :limit";

        $bindings[':offset'] = ($filters['page'] - 1) * $filters['per_page'];
        $bindings[':limit'] = $filters['page'] * $filters['per_page'];

        $stmt = oci_parse($conn, $query);
        foreach ($bindings as $param => $value) {
            oci_bind_by_name($stmt, $param, $bindings[$param]);
        }

        if (!oci_execute($stmt)) {
            throw new Exception("Execute error: " . oci_error($stmt)['message']);
        }

        $items = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $items[] = self::processData($row);
        }

        oci_free_statement($stmt);
        return $items;
    }

    public static function getFilteredCountForOwner($conn, $filters = [])
    {
        $query = "
            SELECT COUNT(*) AS total
            FROM MENU_ITEMS mi
            JOIN KITCHENS k ON mi.KITCHEN_ID = k.KITCHEN_ID
            JOIN CATEGORIES c ON mi.CATEGORY_ID = c.CATEGORY_ID
            WHERE k.OWNER_ID = :owner_id
            ";

        $bindings = [':owner_id' => $filters['owner_id']];

        if (!empty($filters['category'])) {
            $query .= " AND LOWER(c.NAME) = :category_name";
            $bindings[':category_name'] = strtolower($filters['category']);
        }

        if (!empty($filters['search'])) {
            $query .= " AND (
            LOWER(mi.name) LIKE '%' || :search || '%' 
            OR LOWER(mi.description) LIKE '%' || :search || '%' 
            OR LOWER(mi.tags) LIKE '%' || :search || '%')";
            $bindings[':search'] = strtolower($filters['search']);
        }

        // Status filter
        if ($filters['status'] === 'public') {
            $query .= " AND mi.AVAILABLE = 1";
        } elseif ($filters['status'] === 'private') {
            $query .= " AND mi.AVAILABLE = 0";
        }

        $stmt = oci_parse($conn, $query);
        foreach ($bindings as $param => $value) {
            oci_bind_by_name($stmt, $param, $bindings[$param]);
        }

        if (!oci_execute($stmt)) {
            throw new Exception("Execute error: " . oci_error($stmt)['message']);
        }

        $row = oci_fetch_assoc($stmt);
        oci_free_statement($stmt);

        return (int) $row['TOTAL'];
    }

    public static function getItemsReviews($conn)
    {

    }

    public static function getAllMenuItems($conn)
    {
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        try {
            $stmt = oci_parse($conn, self::GET_MENU_ITEM);

            if (!oci_execute($stmt)) {
                throw new Exception("Execute error: " . oci_error($stmt)['message']);
            }

            $items = [];
            while ($row = oci_fetch_assoc($stmt)) {
                $items[] = self::processData($row);
            }

            return $items;

        } finally {
            if (isset($stmt)) {
                oci_free_statement($stmt);
            }

        }

    }

    public static function getItemsByOwner($conn, $owner_id)
    {
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        if (!$owner_id) {
            throw new Exception("Sorry, The Owner ID cannot be null");
        }

        $kitchen_id = self::getKitchenIdByOwner($conn, $owner_id);

        if (!$kitchen_id) {
            throw new Exception("No kitchen found for this owner");
        }

        return self::getMenuItemsByKitchenId($conn, $kitchen_id);
    }

    public static function create($conn, array $data): int
    {
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        if (!isset($data['owner_id'])) {
            throw new Exception("Sorry, The Owner ID cannot be null");
        }

        $kitchen_id = self::getKitchenIdByOwner($conn, $data['owner_id']);
        if (!$kitchen_id) {
            throw new Exception("No kitchen found for this owner");
        }

        $category_id = $data['category_id'];
        $name = $data['name'];
        $description = $data['description'] ?? '';
        $price = $data['price'];
        $item_image = $data['item_image'] ?? null;
        $available = $data['available'] ?? 1;
        $tags = $data['tags'] ?? '';
        $item_id = 0;

        try {
            $stmt = oci_parse($conn, self::CREATE_ITEM);

            oci_bind_by_name($stmt, ':kitchen_id', $kitchen_id);
            oci_bind_by_name($stmt, ':category_id', $category_id);
            oci_bind_by_name($stmt, ':name', $name);
            oci_bind_by_name($stmt, ':description', $description);
            oci_bind_by_name($stmt, ':price', $price);
            oci_bind_by_name($stmt, ':item_image', $item_image);
            oci_bind_by_name($stmt, ':available', $available);
            oci_bind_by_name($stmt, ':tags', $tags);
            oci_bind_by_name($stmt, ':item_id', $item_id, -1, SQLT_INT);

            if (!oci_execute($stmt)) {
                throw new Exception("Failed to create menu item: " . oci_error($stmt)['message']);
            }

            return $item_id;
        } finally {
            if (isset($stmt)) {
                oci_free_statement($stmt);
            }
        }
    }

    public static function update($conn, $item_id, array $data): bool
    {
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        if (empty($item_id) || !is_numeric($item_id)) {
            throw new Exception('Invalid item ID');
        }

        if (!isset($data['owner_id'])) {
            throw new Exception("Owner ID is required for update");
        }

        $kitchen_id = self::getKitchenIdByOwner($conn, $data['owner_id']);
        if (!$kitchen_id) {
            throw new Exception("No kitchen found for this owner");
        }

        $category_id = $data['category_id'];
        $name = $data['name'];
        $description = $data['description'] ?? '';
        $price = $data['price'];
        $item_image = $data['item_image'] ?? null;
        $available = $data['available'] ?? 1;
        $tags = $data['tags'] ?? '';

        $stmt = oci_parse($conn, self::UPDATE_ITEM);

        oci_bind_by_name($stmt, ':item_id', $item_id);
        oci_bind_by_name($stmt, ':kitchen_id', $kitchen_id);
        oci_bind_by_name($stmt, ':category_id', $category_id);
        oci_bind_by_name($stmt, ':name', $name);
        oci_bind_by_name($stmt, ':description', $description);
        oci_bind_by_name($stmt, ':price', $price);
        oci_bind_by_name($stmt, ':item_image', $item_image);
        oci_bind_by_name($stmt, ':available', $available);
        oci_bind_by_name($stmt, ':tags', $tags);

        $success = oci_execute($stmt);
        if (!$success) {
            throw new Exception("Failed to update menu item: " . oci_error($stmt)['message']);
        }

        oci_free_statement($stmt);
        return $success;
    }

    public static function delete($conn, $item_id): bool
    {
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        if (empty($item_id) || !is_numeric($item_id)) {
            throw new Exception("Invalid item ID");
        }

        $owner_id = $_SESSION['user_id'] ?? null;
        if (!$owner_id) {
            throw new Exception("Sorry, The Owner ID cannot be null");
        }

        $kitchen_id = self::getKitchenIdByOwner($conn, $owner_id);
        if (!$kitchen_id) {
            throw new Exception("No kitchen found for this owner");
        }

        $stmt = oci_parse($conn, self::DELETE_ITEM);
        oci_bind_by_name($stmt, ':item_id', $item_id);
        oci_bind_by_name($stmt, ':kitchen_id', $kitchen_id);

        $success = oci_execute($stmt);
        if (!$success) {
            throw new Exception("Failed to delete menu item: " . oci_error($stmt)['message']);
        }

        oci_free_statement($stmt);
        return $success;
    }

    public static function isOwner($conn, $item_id, $owner_id): bool
    {
        if (!$conn) {
            throw new Exception("Database connection failed");
        }
        if (!$owner_id) {
            throw new Exception("Sorry, The Owner ID cannot be null");
        }

        if (empty($item_id) || !is_numeric($item_id)) {
            throw new Exception("Invalid item ID");
        }

        $stmt = oci_parse($conn, self::CHECK_OWNERSHIP);
        oci_bind_by_name($stmt, ':item_id', $item_id);
        oci_bind_by_name($stmt, ':owner_id', $owner_id);

        if (!oci_execute($stmt)) {
            throw new Exception("Ownership check failed: " . oci_error($stmt)['message']);
        }

        $result = oci_fetch_assoc($stmt);
        oci_free_statement($stmt);

        return (bool) $result;
    }

    public static function getAvailability($conn, $item_id): bool
    {
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        if (empty($item_id) || !is_numeric($item_id)) {
            throw new Exception("Invalid item ID");
        }

        $stmt = oci_parse($conn, self::GET_AVAILABILITY);
        oci_bind_by_name($stmt, ':item_id', $item_id);

        if (!oci_execute($stmt)) {
            throw new Exception("Failed to get availability: " . oci_error($stmt)['message']);
        }

        $row = oci_fetch_assoc($stmt);
        oci_free_statement($stmt);

        return (bool) ($row['AVAILABLE'] ?? false);
    }

    public static function getMenuItemsByKitchenId($conn, $kitchen_id)
    {
        $stmt = null;
        try {
            $stmt = oci_parse($conn, self::GET_ITEMS_BY_KITCHEN_ID);
            oci_bind_by_name($stmt, ':kitchen_id', $kitchen_id);

            if (!oci_execute($stmt)) {
                throw new Exception("Execute error: " . oci_error($stmt)['message']);
            }

            $items = [];
            while ($row = oci_fetch_assoc($stmt)) {
                $items[] = self::processData($row);
            }

            return $items;
        } finally {
            if (isset($stmt)) {
                oci_free_statement($stmt);
            }
        }
    }

    protected static function getKitchenIdByOwner($conn, $owner_id)
    {
        $stmt = null;
        try {
            $stmt = oci_parse($conn, self::GET_KITCHEN_ID_BY_OWNER);
            oci_bind_by_name($stmt, ':owner_id', $owner_id);

            if (!oci_execute($stmt)) {
                throw new Exception("Execute error: " . oci_error($stmt)['message']);
            }

            $row = oci_fetch_assoc($stmt);

            return $row ? $row['KITCHEN_ID'] : null;

        } finally {
            if (isset($stmt)) {
                oci_free_statement($stmt);
            }
        }
    }

    public static function getItem($conn, $owner_id, $item_id)
    {
        $kitchen_id = self::getKitchenIdByOwner($conn, $owner_id);

        if (!$kitchen_id) {
            throw new Exception("No kitchen found for this owner");
        }

        $stmt = oci_parse($conn, self::GET_ITEM_BY_ID);
        oci_bind_by_name($stmt, ':item_id', $item_id);
        oci_bind_by_name($stmt, ':kitchen_id', $kitchen_id);

        if (!oci_execute($stmt)) {
            throw new Exception("Execute error: " . oci_error($stmt)['message']);
        }

        $row = oci_fetch_assoc($stmt);
        oci_free_statement($stmt);

        return $row ? self::processData($row) : null;
    }

    protected static function processData(array $row): array
    {
        return [
            'item_id' => $row['ITEM_ID'],
            'kitchen_id' => $row['KITCHEN_ID'],
            'category_id' => $row['CATEGORY_ID'],
            'name' => $row['NAME'],
            'description' => self::loadClob($row['DESCRIPTION']),
            'price' => (float) $row['PRICE'],
            'item_image' => $row['ITEM_IMAGE'],
            'available' => (bool) $row['AVAILABLE'],
            'tags' => $row['TAGS'] ?? '',
            'created_at' => $row['CREATED_AT'],
            'service_areas' => $row['SERVICE_AREAS'] ?? '',
            'avg_rating' => (float) $row['AVG_RATING'] ?? '',
            'review_count' => (int) $row['REVIEW_COUNT' ?? '']
        ];
    }

    protected static function loadClob($clob): string
    {
        return is_object($clob) ? $clob->load() : '';
    }

}
