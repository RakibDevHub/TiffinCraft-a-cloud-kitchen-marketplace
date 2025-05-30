<?php
namespace App\Core;

class Database
{
    private static $connection = null;

    public static function getConnection()
    {
        if (self::$connection === null) {
            $username = 'rakib';
            $password = '4006';
            $connectionString = "
                (DESCRIPTION =
                    (ADDRESS = (PROTOCOL = TCP)(HOST = localhost)(PORT = 1521))
                    (CONNECT_DATA = (SERVICE_NAME = MYPDB))
                )";

            self::$connection = oci_connect($username, $password, $connectionString, 'AL32UTF8');

            if (!self::$connection) {
                $e = oci_error();
                throw new \RuntimeException("Database connection failed: " . $e['message']);
            }
        }

        return self::$connection;
    }

    public static function closeConnection(): void
    {
        if (self::$connection !== null) {
            oci_close(self::$connection);
            self::$connection = null;
        }
    }
}