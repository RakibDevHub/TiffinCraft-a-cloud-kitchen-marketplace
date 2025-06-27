<?php

namespace App\Controllers;

use App\Core\Database;

class ErrorController
{
    public function databaseError()
    {
        $error = $_SESSION['database_error'] ?? [
            'message' => 'Database service unavailable',
            'details' => 'Please try again later',
            'timestamp' => date('Y-m-d H:i:s')
        ];

        unset($_SESSION['database_error']);
        http_response_code(503);

        $this->renderView('errors/database', ['error' => $error]);
        return;
    }


    public function unauthorizedError()
    {
        $error = $_SESSION['unauthorized_error'] ?? [
            'message' => 'Access Denied',
            'details' => "You don't have permition to access this page.",
            'timestamp' => date('Y-m-d H:i:s')
        ];

        unset($_SESSION['unauthorized_error']);
        http_response_code(403);

        $this->renderView('errors/unauthorized', ['error' => $error]);
        return;
    }

    protected function renderView(string $viewPath, $data = []): void
    {
        extract($data);
        include __DIR__ . '/../views/' . $viewPath . '.php';
    }
}

?>