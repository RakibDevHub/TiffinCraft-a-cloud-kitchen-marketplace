<?php
namespace App\Models;

use Exception;
use App\Core\Database;

class ServiceArea
{

    private const GET_ALL_AREAS = "SELECT * FROM service_areas ORDER BY name";

    public static function getAll($conn): array
    {
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        $stmt = null;
        try {
            $stmt = oci_parse($conn, self::GET_ALL_AREAS);

            if (!oci_execute($stmt)) {
                throw new Exception("Execute error: " . oci_error($stmt)['message']);
            }

            $results = [];
            while ($row = oci_fetch_assoc($stmt)) {
                $results[] = self::processData($row);
            }

            return $results;
        } finally {
            if (isset($stmt)) {
                oci_free_statement($stmt);
            }
        }
    }

    public static function exists($conn, int $kitchenId, string $area): bool
    {
        $stmt = oci_parse($conn, self::getExistsQuery());
        self::bindExistsParameters($stmt, $kitchenId, $area);
        oci_execute($stmt, OCI_NO_AUTO_COMMIT); // Added transaction control

        return oci_fetch($stmt) !== false;
    }

    public static function insert($conn, int $kitchenId, string $area): void
    {
        try {
            $areaId = self::getOrCreateAreaId($conn, $area);
            self::linkKitchenToArea($conn, $kitchenId, $areaId);
        } catch (Exception $e) {
            throw new Exception("ServiceArea insert failed: " . $e->getMessage());
        }
    }

    protected static function processData(array $row): array
    {
        return [
            'area_id' => $row['AREA_ID'],
            'name' => $row['NAME']
        ];
    }

    private static function getAllAreasQuery(): string
    {
        return "SELECT area_id, name FROM service_areas ORDER BY name";
    }

    private static function fetchAllResults($stmt): array
    {
        $results = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $results[] = $row;
        }
        return $results;
    }

    private static function getExistsQuery(): string
    {
        return "SELECT 1
                FROM kitchen_service_areas ksa
                JOIN service_areas sa ON ksa.area_id = sa.area_id
                WHERE ksa.kitchen_id = :kitchen_id AND LOWER(sa.name) = LOWER(:name)";
    }

    private static function bindExistsParameters($stmt, int $kitchenId, string $area): void
    {
        oci_bind_by_name($stmt, ':kitchen_id', $kitchenId);
        oci_bind_by_name($stmt, ':name', $area);
    }

    private static function getOrCreateAreaId($conn, string $area): int
    {
        $areaId = self::findAreaId($conn, $area);

        if ($areaId === null) {
            $areaId = self::createArea($conn, $area);
        }

        return $areaId;
    }

    private static function findAreaId($conn, string $area): ?int
    {
        $stmt = oci_parse($conn, "SELECT area_id FROM service_areas WHERE LOWER(name) = LOWER(:name)");
        oci_bind_by_name($stmt, ':name', $area);
        oci_execute($stmt, OCI_NO_AUTO_COMMIT); // Added transaction control

        if (oci_fetch($stmt)) {
            return oci_result($stmt, 'AREA_ID');
        }

        return null;
    }

    private static function createArea($conn, string $area): int
    {
        $areaId = null;
        $stmt = oci_parse($conn, "INSERT INTO service_areas (name) VALUES (:name) RETURNING area_id INTO :area_id");
        oci_bind_by_name($stmt, ':name', $area);
        oci_bind_by_name($stmt, ':area_id', $areaId, -1, SQLT_INT);

        if (!oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
            throw new Exception("Failed to create service area: " . oci_error($stmt)['message']);
        }

        return $areaId;
    }

    private static function linkKitchenToArea($conn, int $kitchenId, int $areaId): void
    {
        $stmt = oci_parse($conn, "INSERT INTO kitchen_service_areas (kitchen_id, area_id) VALUES (:kitchen_id, :area_id)");
        oci_bind_by_name($stmt, ':kitchen_id', $kitchenId);
        oci_bind_by_name($stmt, ':area_id', $areaId);

        if (!oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
            throw new Exception("Failed to link kitchen to area: " . oci_error($stmt)['message']);
        }
    }
}
?>