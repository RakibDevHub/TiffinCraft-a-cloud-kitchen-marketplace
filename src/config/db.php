<?php
// src/config/db.php

$tns = "
(DESCRIPTION =
    (ADDRESS = (PROTOCOL = TCP)(HOST = localhost)(PORT = 1521))
    (CONNECT_DATA =
        (SERVICE_NAME = MYPDB)
    )
)";

$db_host = 'localhost/mypdb';
$db_username = 'rakib';
$db_password = '4006';

try {
    $conn = oci_connect($db_username, $db_password, $db_host, 'AL32UTF8');

    if (!$conn) {
        $e = oci_error();
        throw new Exception($e['message']);
    }
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>