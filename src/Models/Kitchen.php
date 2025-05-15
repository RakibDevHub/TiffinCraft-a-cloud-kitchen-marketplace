<?php
namespace App\Models;

use Exception;

class Kitchen
{
    public static function create($conn, array $data): int
    {
        self::validateKitchenData($data);

        $sql = self::getInsertQuery();
        $stmt = oci_parse($conn, $sql);

        $kitchenId = null;
        self::bindParameters($stmt, $data, $kitchenId);

        if (!oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
            throw new Exception("Failed to create kitchen: " . oci_error($stmt));
        }

        return $kitchenId;
    }

    private static function validateKitchenData(array $data): void
    {
        $requiredFields = ['owner_id', 'name', 'description', 'address', 'kitchen_image'];

        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }
    }

    private static function getInsertQuery(): string
    {
        return "INSERT INTO kitchens (
                    owner_id, 
                    name, 
                    description, 
                    address, 
                    kitchen_image
                ) VALUES (
                    :owner_id, 
                    :name, 
                    :description, 
                    :address, 
                    :kitchen_image
                ) 
                RETURNING kitchen_id INTO :kitchen_id";
    }

    private static function bindParameters($stmt, array $data, &$kitchenId): void
    {
        oci_bind_by_name($stmt, ':owner_id', $data['owner_id']);
        oci_bind_by_name($stmt, ':name', $data['name']);
        oci_bind_by_name($stmt, ':description', $data['description']);
        oci_bind_by_name($stmt, ':address', $data['address']);
        oci_bind_by_name($stmt, ':kitchen_image', $data['kitchen_image']);
        oci_bind_by_name($stmt, ':kitchen_id', $kitchenId, -1, SQLT_INT);
    }

}