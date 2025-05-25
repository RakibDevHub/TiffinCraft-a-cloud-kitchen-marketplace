<?php
namespace App\Models;

use Exception;

class User
{
    private const USER_COUNT = "SELECT
                                    SUM(CASE WHEN role = 'buyer' THEN 1 ELSE 0 END) AS buyer_count,
                                    SUM(CASE WHEN role = 'seller' THEN 1 ELSE 0 END) AS seller_count,
                                    COUNT(*) AS users_count
                                    FROM users
                                    WHERE role IN ('buyer', 'seller')";

    private const FIND_BY_EMAIL_QUERY = "SELECT * FROM users WHERE email = :email";
    private const INSERT_BUYER_QUERY = "INSERT INTO users (name, email, password, phone_number, address, profile_image, role) 
                                      VALUES (:name, :email, :password, :phone_number, :address, :profile_image, :role)
                                      RETURNING user_id INTO :user_id";
    private const INSERT_SELLER_QUERY = "INSERT INTO users (name, email, phone_number, address, password, profile_image, role) 
                                       VALUES (:name, :email, :phone, :address, :password, :image, 'seller')
                                       RETURNING user_id INTO :user_id";

    private const GET_ALL_USERS = "SELECT user_id, name, email, role, status, phone_number, profile_image, address, created_at
                                    FROM users
                                    WHERE role = 'buyer' OR role = 'seller'
                                    ORDER BY created_at DESC";

    public static function getUserCount($conn)
    {
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        $stmt = oci_parse($conn, self::USER_COUNT);
        if (!$stmt) {
            $error = oci_error($conn);
            throw new Exception("Parse error: " . $error['message']);
        }

        $success = oci_execute($stmt);
        if (!$success) {
            $error = oci_error($stmt);
            throw new Exception("Execute error: " . $error['message']);
        }

        try {
            $row = oci_fetch_assoc($stmt);
            if ($row) {
                return array_change_key_case($row, CASE_LOWER);
            } else {
                return ['buyer_count' => 0, 'seller_count' => 0, 'users_count' => 0];
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return ['buyer_count' => 0, 'seller_count' => 0, 'users_count' => 0];
        } finally {
            oci_free_statement($stmt);
        }
    }


    public static function getUsers($conn)
    {
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        $stmt = oci_parse($conn, self::GET_ALL_USERS);
        if (!$stmt) {
            $error = oci_error($conn);
            throw new Exception("Parse error: " . $error['message']);
        }

        $success = oci_execute($stmt); // Store the result of oci_execute
        if (!$success) {
            $error = oci_error($stmt);
            throw new Exception("Execute error: " . $error['message']);
        }

        try {
            $users = [];
            while ($row = oci_fetch_assoc($stmt)) {
                $users[] = array_change_key_case($row, CASE_LOWER);
            }

            return $users;

        } catch (Exception $e) {
            error_log($e->getMessage());
            return [];
        } finally {
            if (isset($stmt)) {
                oci_free_statement($stmt);
            }
        }
    }

    public static function findByEmail($conn, string $email)
    {
        $stmt = oci_parse($conn, self::FIND_BY_EMAIL_QUERY);
        oci_bind_by_name($stmt, ':email', $email);
        oci_execute($stmt);

        $user = oci_fetch_assoc($stmt);
        oci_free_statement($stmt);

        return $user ? array_change_key_case($user, CASE_LOWER) : false;
    }

    public static function registerBuyer($conn, array $data)
    {
        self::validateUserData($data, ['name', 'email', 'password', 'phone_number', 'address', 'profile_image', 'role']);

        try {
            self::beginTransaction($conn);

            $stmt = oci_parse($conn, self::INSERT_BUYER_QUERY);
            $userId = null;

            self::bindBuyerParameters($stmt, $data, $userId);

            if (oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
                self::commitTransaction($conn);
                return $userId;
            }

            throw new Exception("Failed to execute buyer registration query");

        } catch (Exception $e) {
            self::rollbackTransaction($conn);
            return false;
        } finally {
            self::cleanupStatement($stmt ?? null);
        }
    }

    public static function registerSeller($conn, array $data): int
    {
        self::validateUserData($data, ['name', 'email', 'phone', 'address', 'password', 'image']);

        $stmt = oci_parse($conn, self::INSERT_SELLER_QUERY);

        $userId = null;
        self::bindSellerParameters($stmt, $data, $userId);

        if (!oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
            throw new Exception("Failed to create seller user");
        }

        return $userId;
    }

    private static function validateUserData(array $data, array $requiredFields): void
    {
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }
    }

    private static function beginTransaction($conn): void
    {
        oci_execute(oci_parse($conn, "BEGIN NULL; END;"), OCI_NO_AUTO_COMMIT);
    }

    private static function commitTransaction($conn): void
    {
        oci_commit($conn);
    }

    private static function rollbackTransaction($conn): void
    {
        oci_rollback($conn);
    }

    private static function cleanupStatement($stmt): void
    {
        if ($stmt) {
            oci_free_statement($stmt);
        }
    }

    private static function bindBuyerParameters($stmt, array $data, &$userId): void
    {
        oci_bind_by_name($stmt, ':name', $data['name']);
        oci_bind_by_name($stmt, ':email', $data['email']);
        oci_bind_by_name($stmt, ':password', $data['password']);
        oci_bind_by_name($stmt, ':phone_number', $data['phone_number']);
        oci_bind_by_name($stmt, ':address', $data['address']);
        oci_bind_by_name($stmt, ':profile_image', $data['profile_image']);
        oci_bind_by_name($stmt, ':role', $data['role']);
        oci_bind_by_name($stmt, ':user_id', $userId, -1, SQLT_INT);
    }

    private static function bindSellerParameters($stmt, array $data, &$userId): void
    {
        oci_bind_by_name($stmt, ':name', $data['name']);
        oci_bind_by_name($stmt, ':email', $data['email']);
        oci_bind_by_name($stmt, ':phone', $data['phone']);
        oci_bind_by_name($stmt, ':address', $data['address']);
        oci_bind_by_name($stmt, ':password', $data['password']);
        oci_bind_by_name($stmt, ':image', $data['image']);
        oci_bind_by_name($stmt, ':user_id', $userId, -1, SQLT_INT);
    }
}