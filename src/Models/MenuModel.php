<?php
namespace App\Models;

class Menu
{
    private const GET_KITCHEN_ID_BY_OWNER = "SELECT KITCHEN_ID FROM KITCHENS WHERE OWNER_ID = :owner_id";
    private const GET_ITEMS_BY_KITCHEN_ID = "SELECT * FROM MENU_ITEMS WHERE KITCHEN_ID = :kitchen_id";
    private const GET_ITEM_BY_ID = "SELECT * FROM MENU_ITEMS WHERE ITEM_ID = :item_id AND KITCHEN_ID = :kitchen_id";

    public static function getItemsByOwner($conn, $owner_id)
    {
        if (!$conn) {
            throw new \RuntimeException("Database connection failed");
        }

        if (!$owner_id) {
            throw new \InvalidArgumentException("Owner ID cannot be null");
        }

        // First get the kitchen ID for this owner
        $kitchen_id = self::getKitchenIdByOwner($conn, $owner_id);
        
        if (!$kitchen_id) {
            throw new \RuntimeException("No kitchen found for this owner");
        }

        // Then get all items for this kitchen
        return self::getItemsByKitchenId($conn, $kitchen_id);
    }

    protected static function getKitchenIdByOwner($conn, $owner_id)
    {
        $stmt = oci_parse($conn, self::GET_KITCHEN_ID_BY_OWNER);
        oci_bind_by_name($stmt, ':owner_id', $owner_id);
        
        if (!oci_execute($stmt)) {
            throw new \RuntimeException("Execute error: " . oci_error($stmt)['message']);
        }

        $row = oci_fetch_assoc($stmt);
        oci_free_statement($stmt);
        
        return $row ? $row['KITCHEN_ID'] : null;
    }

    protected static function getItemsByKitchenId($conn, $kitchen_id)
    {
        $stmt = oci_parse($conn, self::GET_ITEMS_BY_KITCHEN_ID);
        oci_bind_by_name($stmt, ':kitchen_id', $kitchen_id);
        
        if (!oci_execute($stmt)) {
            throw new \RuntimeException("Execute error: " . oci_error($stmt)['message']);
        }

        $items = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $items[] = self::processItemData($row);
        }
        
        oci_free_statement($stmt);
        return $items;
    }

    public static function getItem($conn, $owner_id, $item_id)
    {
        $kitchen_id = self::getKitchenIdByOwner($conn, $owner_id);
        
        if (!$kitchen_id) {
            throw new \RuntimeException("No kitchen found for this owner");
        }

        $stmt = oci_parse($conn, self::GET_ITEM_BY_ID);
        oci_bind_by_name($stmt, ':item_id', $item_id);
        oci_bind_by_name($stmt, ':kitchen_id', $kitchen_id);
        
        if (!oci_execute($stmt)) {
            throw new \RuntimeException("Execute error: " . oci_error($stmt)['message']);
        }

        $row = oci_fetch_assoc($stmt);
        oci_free_statement($stmt);
        
        return $row ? self::processItemData($row) : null;
    }

    protected static function processItemData(array $row): array
    {
        return [
            'item_id' => $row['ITEM_ID'],
            'kitchen_id' => $row['KITCHEN_ID'],
            'category_id' => $row['CATEGORY_ID'],
            'name' => $row['NAME'],
            'description' => $row['DESCRIPTION']->load() ?? '',
            'price' => (float)$row['PRICE'],
            'item_image' => $row['ITEM_IMAGE'],
            'available' => (bool)$row['AVAILABLE'],
            'created_at' => $row['CREATED_AT']
        ];
    }
}