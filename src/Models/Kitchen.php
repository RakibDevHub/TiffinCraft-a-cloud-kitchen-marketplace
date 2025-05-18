<?php
namespace App\Models;

use Exception;

class Kitchen
{
    private const GET_ALL_KITCHENS = "
        SELECT 
            k.kitchen_id,
            k.owner_id,
            u.name as owner_name,
            u.email as owner_email,
            u.phone_number as owner_phone,
            k.name,
            k.description,
            k.address,
            k.kitchen_image,
            k.created_at,
            k.is_approved,
            (SELECT COUNT(*) FROM kitchen_reviews WHERE kitchen_id = k.kitchen_id) as review_count,
            (SELECT AVG(rating) FROM kitchen_reviews WHERE kitchen_id = k.kitchen_id) as avg_rating,
            (SELECT LISTAGG(sa.name, ', ') WITHIN GROUP (ORDER BY sa.name) 
            FROM service_areas sa
            JOIN kitchen_service_areas ksa ON sa.area_id = ksa.area_id
            WHERE ksa.kitchen_id = k.kitchen_id) as service_areas
        FROM 
            kitchens k
        JOIN 
            users u ON k.owner_id = u.user_id
        ORDER BY 
            k.created_at DESC";

    private const GET_KITCHEN_BY_ID = "
        SELECT 
            k.*,
            u.name as owner_name,
            u.email as owner_email,
            u.phone_number as owner_phone,
            (SELECT COUNT(*) FROM kitchen_reviews WHERE kitchen_id = k.kitchen_id) as review_count,
            (SELECT AVG(rating) FROM kitchen_reviews WHERE kitchen_id = k.kitchen_id) as avg_rating,
            (SELECT LISTAGG(sa.name, ', ') WITHIN GROUP (ORDER BY sa.name) 
            FROM service_areas sa
            JOIN kitchen_service_areas ksa ON sa.area_id = ksa.area_id
            WHERE ksa.kitchen_id = k.kitchen_id) as service_areas
        FROM 
            kitchens k
        JOIN 
            users u ON k.owner_id = u.user_id
        WHERE 
            k.kitchen_id = :kitchen_id";

    private const GET_KITCHENS_BY_OWNER = "
        SELECT 
            k.*,
            (SELECT LISTAGG(sa.name, ', ') WITHIN GROUP (ORDER BY sa.name) 
            FROM service_areas sa
            JOIN kitchen_service_areas ksa ON sa.area_id = ksa.area_id
            WHERE ksa.kitchen_id = k.kitchen_id) as service_areas
        FROM kitchens k
        WHERE owner_id = :owner_id
        ORDER BY created_at DESC";

    private const INSERT_KITCHEN_QUERY = "
        INSERT INTO kitchens (
            owner_id, 
            name, 
            description, 
            address, 
            kitchen_image, 
            is_approved
        ) VALUES (
            :owner_id, 
            :name, 
            :description, 
            :address, 
            :kitchen_image, 
            0
        ) RETURNING kitchen_id INTO :kitchen_id";

    private const UPDATE_KITCHEN_QUERY = "
        UPDATE kitchens SET
            name = :name,
            description = :description,
            address = :address,
            kitchen_image = :kitchen_image
        WHERE 
            kitchen_id = :kitchen_id";

    private const APPROVE_KITCHEN_QUERY = "
        UPDATE kitchens SET
            is_approved = 1
        WHERE 
            kitchen_id = :kitchen_id";

    private const REJECT_KITCHEN_QUERY = "
        UPDATE kitchens SET
            is_approved = 2
        WHERE 
            kitchen_id = :kitchen_id";

    private const DELETE_KITCHEN_QUERY = "
        DELETE FROM kitchens
        WHERE kitchen_id = :kitchen_id";

    /**
     * Get all kitchens with owner details
     */
    public static function getAll($conn)
    {
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        $stmt = oci_parse($conn, self::GET_ALL_KITCHENS);
        if (!$stmt) {
            $error = oci_error($conn);
            throw new Exception("Parse error: " . $error['message']);
        }

        if (!oci_execute($stmt)) {
            $error = oci_error($stmt);
            throw new Exception("Execute error: " . $error['message']);
        }

        try {
            $kitchens = [];
            while ($row = oci_fetch_assoc($stmt)) {
                $kitchens[] = array_change_key_case($row, CASE_LOWER);
            }
            return $kitchens;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return [];
        } finally {
            oci_free_statement($stmt);
        }
    }

    /**
     * Get kitchen by ID with owner details
     */
    public static function getById($conn, int $kitchenId)
    {
        $stmt = oci_parse($conn, self::GET_KITCHEN_BY_ID);
        oci_bind_by_name($stmt, ':kitchen_id', $kitchenId);

        if (!oci_execute($stmt)) {
            throw new Exception("Failed to fetch kitchen: " . oci_error($stmt));
        }

        $kitchen = oci_fetch_assoc($stmt);
        oci_free_statement($stmt);

        return $kitchen ? array_change_key_case($kitchen, CASE_LOWER) : null;
    }

    /**
     * Get kitchens by owner ID
     */
    public static function getByOwner($conn, int $ownerId)
    {
        $stmt = oci_parse($conn, self::GET_KITCHENS_BY_OWNER);
        oci_bind_by_name($stmt, ':owner_id', $ownerId);

        if (!oci_execute($stmt)) {
            throw new Exception("Failed to fetch owner kitchens: " . oci_error($stmt));
        }

        $kitchens = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $kitchens[] = array_change_key_case($row, CASE_LOWER);
        }
        oci_free_statement($stmt);

        return $kitchens;
    }

    /**
     * Create a new kitchen
     */
    public static function create($conn, array $data): int
    {
        self::validateKitchenData($data);

        $stmt = oci_parse($conn, self::INSERT_KITCHEN_QUERY);
        $kitchenId = null;

        oci_bind_by_name($stmt, ':owner_id', $data['owner_id']);
        oci_bind_by_name($stmt, ':name', $data['name']);
        oci_bind_by_name($stmt, ':description', $data['description']);
        oci_bind_by_name($stmt, ':address', $data['address']);
        oci_bind_by_name($stmt, ':kitchen_image', $data['kitchen_image']);
        oci_bind_by_name($stmt, ':kitchen_id', $kitchenId, -1, SQLT_INT);

        if (!oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
            throw new Exception("Failed to create kitchen: " . oci_error($stmt));
        }

        oci_commit($conn);
        oci_free_statement($stmt);

        return $kitchenId;
    }

    /**
     * Update kitchen details
     */
    public static function update($conn, int $kitchenId, array $data): bool
    {
        self::validateKitchenData($data);

        $stmt = oci_parse($conn, self::UPDATE_KITCHEN_QUERY);

        oci_bind_by_name($stmt, ':kitchen_id', $kitchenId);
        oci_bind_by_name($stmt, ':name', $data['name']);
        oci_bind_by_name($stmt, ':description', $data['description']);
        oci_bind_by_name($stmt, ':address', $data['address']);
        oci_bind_by_name($stmt, ':kitchen_image', $data['kitchen_image']);

        $success = oci_execute($stmt, OCI_NO_AUTO_COMMIT);
        if (!$success) {
            throw new Exception("Failed to update kitchen: " . oci_error($stmt));
        }

        oci_commit($conn);
        oci_free_statement($stmt);

        return $success;
    }

    /**
     * Approve a kitchen
     */
    public static function approve($conn, int $kitchenId): bool
    {
        $stmt = oci_parse($conn, self::APPROVE_KITCHEN_QUERY);
        oci_bind_by_name($stmt, ':kitchen_id', $kitchenId);

        $success = oci_execute($stmt, OCI_NO_AUTO_COMMIT);
        if (!$success) {
            throw new Exception("Failed to approve kitchen: " . oci_error($stmt));
        }

        oci_commit($conn);
        oci_free_statement($stmt);

        return $success;
    }

    /**
     * Reject a kitchen
     */
    public static function reject($conn, int $kitchenId): bool
    {
        $stmt = oci_parse($conn, self::REJECT_KITCHEN_QUERY);
        oci_bind_by_name($stmt, ':kitchen_id', $kitchenId);

        $success = oci_execute($stmt, OCI_NO_AUTO_COMMIT);
        if (!$success) {
            throw new Exception("Failed to reject kitchen: " . oci_error($stmt));
        }

        oci_commit($conn);
        oci_free_statement($stmt);

        return $success;
    }

    /**
     * Delete a kitchen
     */
    public static function delete($conn, int $kitchenId): bool
    {
        $stmt = oci_parse($conn, self::DELETE_KITCHEN_QUERY);
        oci_bind_by_name($stmt, ':kitchen_id', $kitchenId);

        $success = oci_execute($stmt, OCI_NO_AUTO_COMMIT);
        if (!$success) {
            throw new Exception("Failed to delete kitchen: " . oci_error($stmt));
        }

        oci_commit($conn);
        oci_free_statement($stmt);

        return $success;
    }

    /**
     * Validate kitchen data
     */
    private static function validateKitchenData(array $data): void
    {
        $requiredFields = ['owner_id', 'name', 'description', 'address', 'kitchen_image'];

        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }

        if (!is_numeric($data['owner_id'])) {
            throw new Exception("Owner ID must be numeric");
        }
    }
}