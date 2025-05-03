<?php

namespace App\Models;

class Kitchen
{
    public static function create($conn, $data)
    {
        $sql = "INSERT INTO kitchens (owner_id, name, description, address, kitchen_image) 
                VALUES (:owner_id, :name, :description, :address, :kitchen_image) 
                RETURNING kitchen_id INTO :kitchen_id";
        $stmt = oci_parse($conn, $sql);

        $kitchenId = null;

        oci_bind_by_name($stmt, ':owner_id', $data['owner_id']);
        oci_bind_by_name($stmt, ':name', $data['name']);
        oci_bind_by_name($stmt, ':description', $data['description']);
        oci_bind_by_name($stmt, ':address', $data['address']);
        oci_bind_by_name($stmt, ':kitchen_image', $data['kitchen_image']);
        oci_bind_by_name($stmt, ':kitchen_id', $kitchenId, -1, SQLT_INT);

        if (!oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
            throw new Exception("Failed to create kitchen.");
        }

        return $kitchenId;
    }
}

