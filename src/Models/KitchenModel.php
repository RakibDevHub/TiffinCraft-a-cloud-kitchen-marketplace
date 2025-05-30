<?php
namespace App\Models;

use Exception;
use OCILob;
use DateTime;

class Kitchen
{
    // SQL queries
    private const GET_ALL_KITCHENS = "
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
        FROM kitchens k
        JOIN users u ON k.owner_id = u.user_id
        ORDER BY k.created_at DESC";

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
        FROM kitchens k
        JOIN users u ON k.owner_id = u.user_id
        WHERE k.kitchen_id = :kitchen_id";

    private const GET_KITCHEN_BY_OWNER_ID = "
        SELECT 
            k.*,
            u.name AS owner_name,
            u.email AS owner_email,
            u.phone_number AS owner_phone,
            (SELECT COUNT(*) FROM kitchen_reviews WHERE kitchen_id = k.kitchen_id) AS review_count,
            (SELECT AVG(rating) FROM kitchen_reviews WHERE kitchen_id = k.kitchen_id) AS avg_rating,
            (SELECT LISTAGG(sa.name, ', ') WITHIN GROUP (ORDER BY sa.name) 
            FROM service_areas sa
            JOIN kitchen_service_areas ksa ON sa.area_id = ksa.area_id
            WHERE ksa.kitchen_id = k.kitchen_id) AS service_areas
        FROM kitchens k
        JOIN users u ON k.owner_id = u.user_id
        WHERE k.owner_id = :owner_id";


    private const INSERT_KITCHEN_QUERY = "INSERT INTO kitchens (
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
                ) 
                RETURNING kitchen_id INTO :kitchen_id";

    private const APPROVE_KITCHEN_QUERY = "
        UPDATE kitchens SET
            is_approved = 1
        WHERE kitchen_id = :kitchen_id";

    private const REJECT_KITCHEN_QUERY = "
        UPDATE kitchens SET
            is_approved = 2
        WHERE kitchen_id = :kitchen_id";

    private const SUSPEND_KITCHEN_QUERY = "
        UPDATE kitchens SET
            is_approved = 3
        WHERE kitchen_id = :kitchen_id";


    public static function getAll($conn): array
    {
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        $stmt = oci_parse($conn, self::GET_ALL_KITCHENS);
        if (!$stmt) {
            throw new Exception("Parse error: " . oci_error($conn)['message']);
        }

        if (!oci_execute($stmt)) {
            throw new Exception("Execute error: " . oci_error($stmt)['message']);
        }

        $kitchens = [];
        while ($row = oci_fetch_assoc($stmt)) {
            if (!empty($row['SUSPENDED_UNTIL'])) {
                $row['SUSPENDED_UNTIL'] = self::processOracleDate($row['SUSPENDED_UNTIL']);
            }

            $kitchens[] = self::processKitchenData($row);
        }

        oci_free_statement($stmt);
        return $kitchens;
    }


    public static function getById($conn, int $kitchenId): ?array
    {
        $stmt = oci_parse($conn, self::GET_KITCHEN_BY_ID);
        oci_bind_by_name($stmt, ':kitchen_id', $kitchenId);

        if (!oci_execute($stmt)) {
            throw new Exception("Failed to fetch kitchen: " . oci_error($stmt)['message']);
        }

        $kitchen = oci_fetch_assoc($stmt);

        if ($kitchen && !empty($kitchen['SUSPENDED_UNTIL'])) {
            $kitchen['SUSPENDED_UNTIL'] = self::processOracleDate($kitchen['SUSPENDED_UNTIL']);
        }

        oci_free_statement($stmt);

        return $kitchen ? self::processKitchenData($kitchen) : null;
    }


    public static function getByOwnerId($conn, int $userId): ?array
    {
        $stmt = oci_parse($conn, self::GET_KITCHEN_BY_OWNER_ID);
        oci_bind_by_name($stmt, ':owner_id', $userId);

        if (!oci_execute($stmt)) {
            throw new Exception("Failed to fetch kitchen: " . oci_error($stmt)['message']);
        }

        $kitchen = oci_fetch_assoc($stmt);

        if ($kitchen && !empty($kitchen['SUSPENDED_UNTIL'])) {
            $kitchen['SUSPENDED_UNTIL'] = self::processOracleDate($kitchen['SUSPENDED_UNTIL']);
        }

        oci_free_statement($stmt);

        return $kitchen ? self::processKitchenData($kitchen) : null;
    }

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
            $err = oci_error($stmt);
            throw new Exception("Failed to create kitchen: " . $err['message']);
        }

        oci_free_statement($stmt);

        return $kitchenId;
    }


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

    public static function approve($conn, int $kitchenId): bool
    {
        return self::updateKitchenStatus($conn, $kitchenId, self::APPROVE_KITCHEN_QUERY);
    }

    public static function reject($conn, int $kitchenId): bool
    {
        return self::updateKitchenStatus($conn, $kitchenId, self::REJECT_KITCHEN_QUERY);
    }

    public static function suspend($conn, int $kitchenId): bool
    {
        return self::updateKitchenStatus($conn, $kitchenId, self::SUSPEND_KITCHEN_QUERY);
    }


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

    private static function processKitchenData(array $row): array
    {
        $kitchen = array_change_key_case($row, CASE_LOWER);

        // Process description (COLOB)
        $kitchen['description'] = self::processDescription($kitchen['description'] ?? '');

        // Process dates
        $kitchen['created_at'] = self::processOracleDate($kitchen['created_at'] ?? '');
        $kitchen['suspended_until'] = self::processOracleDate($kitchen['suspended_until'] ?? '');

        // Convert numeric strings
        if (isset($kitchen['is_approved'])) {
            $kitchen['is_approved'] = (int) $kitchen['is_approved'];
        }
        if (isset($kitchen['review_count'])) {
            $kitchen['review_count'] = (int) $kitchen['review_count'];
        }
        if (isset($kitchen['avg_rating'])) {
            $kitchen['avg_rating'] = $kitchen['avg_rating'] !== null ? (float) $kitchen['avg_rating'] : null;
        }

        return $kitchen;
    }

    private static function processDescription($description): string
    {
        if (is_object($description) && get_class($description) === 'OCILob') {
            try {
                return $description->read($description->size()) ?: '';
            } catch (Exception $e) {
                error_log("Error reading OCILob: " . $e->getMessage());
                return '';
            } finally {
                $description->free();
            }
        }
        return (string) $description;
    }

    private static function processOracleDate(string $dateString): string
    {
        try {
            $date = DateTime::createFromFormat('d-M-y h.i.s.u A', strtoupper($dateString));
            return $date ? $date->format(DateTime::ATOM) : $dateString;
        } catch (Exception $e) {
            error_log("Date processing error: " . $e->getMessage());
            return $dateString;
        }
    }

    private static function updateKitchenStatus($conn, int $kitchenId, string $query): bool
    {
        $stmt = oci_parse($conn, $query);
        oci_bind_by_name($stmt, ':kitchen_id', $kitchenId);

        if (!oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
            throw new Exception("Operation failed: " . oci_error($stmt)['message']);
        }

        oci_commit($conn);
        oci_free_statement($stmt);
        return true;
    }
}