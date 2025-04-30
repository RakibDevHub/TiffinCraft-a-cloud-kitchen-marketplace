<?php
namespace App\Models;

class User
{
    public static function findByEmail($email)
    {
        require BASE_PATH . '/src/config/db.php';

        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':email', $email);
        oci_execute($stmt);

        $user = oci_fetch_assoc($stmt);
        oci_free_statement($stmt);
        oci_close($conn);

        return $user ?: null;
    }

    public static function registerBuyer($data)
    {
        require BASE_PATH . '/src/config/db.php';

        // Begin transaction
        oci_execute(oci_parse($conn, "BEGIN NULL; END;"), OCI_NO_AUTO_COMMIT);

        $sql = "INSERT INTO users (name, email, password, phone_number, address, profile_image, role) 
                VALUES (:name, :email, :password, :phone_number, :address, :profile_image, :role)
                RETURNING user_id INTO :user_id";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':name', $data['name']);
        oci_bind_by_name($stmt, ':email', $data['email']);
        oci_bind_by_name($stmt, ':password', $data['password']);
        oci_bind_by_name($stmt, ':phone_number', $data['phone_number']);
        oci_bind_by_name($stmt, ':address', $data['address']);
        oci_bind_by_name($stmt, ':profile_image', $data['profile_image']);
        oci_bind_by_name($stmt, ':role', $data['role']);
        oci_bind_by_name($stmt, ':user_id', $user_id, -1, SQLT_INT);

        if (oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
            oci_commit($conn);
            oci_free_statement($stmt);
            oci_close($conn);
            return $user_id;
        } else {
            oci_rollback($conn);
            oci_free_statement($stmt);
            oci_close($conn);
            return false;
        }
    }

    public static function registerSeller($data)
    {

    }
}
