<?php
namespace App\Controllers;

use App\Core\Database;

class BaseController
{
    public function __construct()
    {
        if (!Database::isAvailable()) {
            $_SESSION['database_error_redirected'] = true;
            header('Location: /database-error');
            exit;
        }
    }

    protected function db()
    {
        return Database::getConnection();
    }
}
