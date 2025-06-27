<?php
namespace App\Models;

use Exception;

class Category
{
    private const GET_ALL_CATEGORIES = "SELECT * FROM CATEGORIES ORDER BY CATEGORY_ID";
    private const GET_CATEGORY_BY_ID = "SELECT * FROM CATEGORIES WHERE CATEGORY_ID = :category_id";

    private const CREATE_CATEGORY = "INSERT INTO CATEGORIES (NAME, DESCRIPTION, IMAGE) VALUES (:name, :description, :image) RETURNING CATEGORY_ID INTO :category_id";
    private const DELETE_CATEGORY = "DELETE FROM CATEGORIES WHERE CATEGORY_ID = :category_id";
    private const UPDATE_CATEGORY = "UPDATE CATEGORIES SET NAME = :name, DESCRIPTION = :description, IMAGE = :image WHERE CATEGORY_ID = :category_id";



    public static function getAllCategories($conn): array
    {
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        $stmt = oci_parse($conn, self::GET_ALL_CATEGORIES);
        if (!$stmt) {
            throw new Exception("Failed to prepare query: " . oci_error($conn)['message']);
        }

        if (!oci_execute($stmt)) {
            throw new Exception("Execute error: " . oci_error($stmt)['message']);
        }

        $categories = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $categories[] = array_change_key_case($row, CASE_LOWER);
        }

        oci_free_statement($stmt);
        return $categories;
    }

    public static function getById($conn, int $categoryId): ?array
    {
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        $stmt = oci_parse($conn, self::GET_CATEGORY_BY_ID);
        if (!$stmt) {
            throw new Exception("Failed to prepare query: " . oci_error($conn)['message']);
        }

        oci_bind_by_name($stmt, ':category_id', $categoryId);

        if (!oci_execute($stmt)) {
            throw new Exception("Execute error: " . oci_error($stmt)['message']);
        }

        $row = oci_fetch_assoc($stmt);
        oci_free_statement($stmt);

        return $row ? array_change_key_case($row, CASE_LOWER) : null;
    }

    public static function create($conn, array $data): int
    {
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        $name = $data['name'];
        $description = $data['description'] ?? '';
        $image = $data['image'] ?? null;
        $categoryId = 0;

        try {
            $stmt = oci_parse($conn, self::CREATE_CATEGORY);

            oci_bind_by_name($stmt, ':name', $name);
            oci_bind_by_name($stmt, ':description', $description);
            oci_bind_by_name($stmt, ':image', $image);
            oci_bind_by_name($stmt, ':category_id', $categoryId, -1, SQLT_INT);

            if (!oci_execute($stmt)) {
                throw new Exception("Failed to prepare query: " . oci_error($stmt)['message']);
            }

            return $categoryId;
        } finally {
            if (isset($stmt)) {
                oci_free_statement($stmt);
            }
        }
    }

    public static function update($conn, $categoryId, array $data): bool
    {
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        if (empty($categoryId) || !is_numeric($categoryId)) {
            throw new Exception("Invalid category ID");
        }

        $name = $data['name'];
        $description = $data['description'] ?? '';
        $image = $data['image'] ?? null;

        $stmt = oci_parse($conn, self::UPDATE_CATEGORY);

        oci_bind_by_name($stmt, ':category_id', $categoryId);
        oci_bind_by_name($stmt, ':name', $name);
        oci_bind_by_name($stmt, ':description', $description);
        oci_bind_by_name($stmt, ':image', $image);

        $success = oci_execute($stmt);

        if (!$success) {
            throw new Exception("Failed to update category: " . oci_error($stmt)['message']);
        }

        oci_free_statement($stmt);
        return $success;
    }

    public static function delete($conn, $categoryId): bool
    {
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        if (empty($categoryId) || !is_numeric($categoryId)) {
            throw new Exception("Invalid Category ID");
        }

        $stmt = oci_parse($conn, self::DELETE_CATEGORY);
        oci_bind_by_name($stmt, ':category_id', $categoryId);

        $success = oci_execute($stmt);

        if (!$success) {
            throw new Exception("Failed to delete category: " . oci_error($stmt)['message']);
        }

        oci_free_statement($stmt);
        return $success;
    }

}