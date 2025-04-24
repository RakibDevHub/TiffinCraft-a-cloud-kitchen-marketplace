<?php
session_start();
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $address = $_POST['address'];

    oci_execute(oci_parse($conn, "BEGIN NULL; END;"), OCI_NO_AUTO_COMMIT);

    $sql_user = "INSERT INTO users (name, email, password, role) 
                 VALUES (:name, :email, :password, 'buyer') 
                 RETURNING id INTO :user_id";
    $stmt_user = oci_parse($conn, $sql_user);
    oci_bind_by_name($stmt_user, ':name', $name);
    oci_bind_by_name($stmt_user, ':email', $email);
    oci_bind_by_name($stmt_user, ':password', $password);
    oci_bind_by_name($stmt_user, ':user_id', $user_id, -1, SQLT_INT);

    if (oci_execute($stmt_user, OCI_NO_AUTO_COMMIT)) {
        $sql_buyer = "INSERT INTO buyers (user_id, address) 
                      VALUES (:user_id, :address)";
        $stmt_buyer = oci_parse($conn, $sql_buyer);
        oci_bind_by_name($stmt_buyer, ':user_id', $user_id);
        oci_bind_by_name($stmt_buyer, ':address', $address);

        if (oci_execute($stmt_buyer, OCI_NO_AUTO_COMMIT)) {
            oci_commit($conn);
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = 'buyer';
            header("Location: /buyer/dashboard.php");
            exit;
        } else {
            oci_rollback($conn);
            echo "Error creating buyer profile.";
        }
    } else {
        oci_rollback($conn);
        echo "Error registering user.";
    }
}

?>