<?php

namespace App\Models;

use App\Core\Database;
use Exception;

class ServiceArea
{
    // Get all available areas
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

    // Check if a kitchen is already linked to an area
    public static function exists($conn, $kitchenId, $area)
    {
        $sql = "SELECT 1
                FROM kitchen_service_areas ksa
                JOIN service_areas sa ON ksa.area_id = sa.area_id
                WHERE ksa.kitchen_id = :kitchen_id AND LOWER(sa.name) = LOWER(:name)";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':kitchen_id', $kitchenId);
        oci_bind_by_name($stmt, ':name', $area);
        oci_execute($stmt);
        return oci_fetch($stmt) !== false;
    }

    // Insert area (if not exists) and link to kitchen
    public static function insert($conn, $kitchenId, $area)
    {
        try {
            // 1. Get or insert the area
            $stmt = oci_parse($conn, "SELECT area_id FROM service_areas WHERE LOWER(name) = LOWER(:name)");
            oci_bind_by_name($stmt, ':name', $area);
            oci_execute($stmt);

            $areaId = null;
            if (oci_fetch($stmt)) {
                $areaId = oci_result($stmt, 'AREA_ID');
            } else {
                $insertArea = oci_parse($conn, "INSERT INTO service_areas (name) VALUES (:name) RETURNING area_id INTO :area_id");
                oci_bind_by_name($insertArea, ':name', $area);
                oci_bind_by_name($insertArea, ':area_id', $areaId, -1, SQLT_INT);
                oci_execute($insertArea);
            }

            // 2. Link kitchen and area
            $linkStmt = oci_parse($conn, "INSERT INTO kitchen_service_areas (kitchen_id, area_id) VALUES (:kitchen_id, :area_id)");
            oci_bind_by_name($linkStmt, ':kitchen_id', $kitchenId);
            oci_bind_by_name($linkStmt, ':area_id', $areaId);

            if (!oci_execute($linkStmt, OCI_NO_AUTO_COMMIT)) {
                throw new Exception("Failed to link kitchen to area: $area");
            }
        } catch (Exception $e) {
            throw new Exception("ServiceArea insert failed: " . $e->getMessage());
        }
    }
}
