<?php

namespace App\Controllers;

use App\Core\Database;

class ErrorController
{
    public function databaseError()
    {

        if (!Database::isAvailable()) {
            // Get error details from session or use defaults
            $error = $_SESSION['database_error'] ?? [
                'message' => 'Database service unavailable',
                'details' => 'Please try again later',
                'timestamp' => date('Y-m-d H:i:s')
            ];

            // Clear the error from session
            unset($_SESSION['database_error']);

            // Set proper HTTP status code
            http_response_code(503);

            $this->renderView('errors/database', ['error' => $error]);
        }
        $this->renderView('buyer/home', ['error' => $error]);
    }

    public function unauthorizedError()
    {
        // Get error details from session or use defaults
        $error = $_SESSION['unauthorized_error'] ?? [
            'message' => 'Access Denied',
            'details' => "You don't have permition to access this page.",
            'timestamp' => date('Y-m-d H:i:s')
        ];

        // Clear the error from session
        unset($_SESSION['unauthorized_error']);

        // Set proper HTTP status code
        http_response_code(403);

        $this->renderView('errors/unauthorized', ['error' => $error]);
    }

    protected function renderView(string $viewPath, $data = []): void
    {
        extract($data);
        include __DIR__ . '/../views/' . $viewPath . '.php';
    }
}

?>