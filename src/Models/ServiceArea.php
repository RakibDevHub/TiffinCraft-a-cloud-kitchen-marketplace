<?php

namespace App\Models;

use App\Core\Database;

class ServiceArea
{
    public static function getAll()
    {
        $conn = Database::getConnection();
        $stmt = oci_parse($conn, "SELECT area_id, name FROM service_areas ORDER BY name");
        oci_execute($stmt);

        $areas = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $areas[] = $row;
        }
        return $areas;
    }

    public static function exists($conn, $kitchenId, $area)
    {
        $sql = "SELECT 1 FROM service_areas WHERE kitchen_id = :kitchen_id AND LOWER(name) = LOWER(:name)";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':kitchen_id', $kitchenId);
        oci_bind_by_name($stmt, ':name', $area);
        oci_execute($stmt);
        return oci_fetch($stmt) !== false;
    }

    public static function insert($conn, $kitchenId, $area)
    {
        $sql = "INSERT INTO service_areas (kitchen_id, name) VALUES (:kitchen_id, :name)";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':kitchen_id', $kitchenId);
        oci_bind_by_name($stmt, ':name', $area);

        if (!oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
            throw new Exception("Failed to insert service area: $area");
        }
    }
}
