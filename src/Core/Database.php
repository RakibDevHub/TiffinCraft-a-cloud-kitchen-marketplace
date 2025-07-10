<?php
namespace App\Core;

class Database
{
    private static $connection = null;
    private static $attempted = false;

    public static function getConnection()
    {
        if (self::$connection !== null) {
            return self::$connection;
        }

        // Prevent retries within same request
        if (self::$attempted) {
            return false;
        }

        self::$attempted = true;

        try {
            $username = $_ENV['DB_USERNAME'] ?? 'rakib';
            $password = $_ENV['DB_PASSWORD'] ?? '4006';
            $connectionString = $_ENV['DB_CONNECTION_STRING'] ?? "
                (DESCRIPTION =
                    (ADDRESS = (PROTOCOL = TCP)(HOST = localhost)(PORT = 1521))
                    (CONNECT_DATA = (SERVICE_NAME = MYPDB))
                )";
            $charset = $_ENV['DB_CHARSET'] ?? 'AL32UTF8';

            self::$connection = @oci_connect($username, $password, $connectionString, $charset);

            if (!self::$connection) {
                $e = oci_error();
                throw new \RuntimeException("Database connection failed: " . $e['message']);
            }

            $_SESSION['db_connection_time'] = time();
            unset($_SESSION['db_connection_failed']);
        } catch (\RuntimeException $e) {
            error_log('Database connection error: ' . $e->getMessage());

            // Prevent redirect loops
            if (!isset($_SESSION['db_connection_failed'])) {
                $_SESSION['db_connection_failed'] = true;

                $_SESSION['database_error'] = [
                    'message' => 'We are experiencing database connectivity issues.',
                    'details' => 'Our team has been notified. Please try again later.',
                    'timestamp' => time()
                ];

                header('Location: /database-error');
                exit();
            }
        }

        return self::$connection;
    }

    public static function closeConnection(): void
    {
        if (self::$connection !== null) {
            try {
                oci_close(self::$connection);
                self::$connection = null;

                if (isset($_SESSION['db_connection_time'])) {
                    $duration = time() - $_SESSION['db_connection_time'];
                    error_log("Database connection closed after $duration seconds");
                    unset($_SESSION['db_connection_time']);
                }
            } catch (\Exception $e) {
                error_log('Error closing database connection: ' . $e->getMessage());
            }
        }

        // Reset attempt flag
        self::$attempted = false;
    }

    public static function isAvailable(): bool
    {
        try {
            $conn = self::getConnection();
            return $conn !== false;
        } catch (\Exception $e) {
            return false;
        }
    }

}
