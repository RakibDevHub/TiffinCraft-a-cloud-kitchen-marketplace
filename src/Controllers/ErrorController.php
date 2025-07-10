<?php

namespace App\Controllers;

use App\Core\Database;
use Exception;

class ErrorController
{
    private $maxRedirectAttempts = 3;

    public function databaseError()
    {
        $attempts = $_SESSION['db_error_attempts'] ?? 0;
        $maxAttempts = 3;

        try {
            // Try reconnecting
            $conn = Database::getConnection();

            if ($conn) {
                unset($_SESSION['db_error_attempts']);
                header('Location: /');
                exit;
            }
        } catch (Exception $e) {
        }

        // If not connected increment
        $_SESSION['db_error_attempts'] = $attempts + 1;

        if ($_SESSION['db_error_attempts'] >= $maxAttempts) {
            return $this->showFinalErrorPage();
        }

        $error = $_SESSION['database_error'] ?? [
            'message' => 'Database Connection Error',
            'details' => 'We’re having trouble connecting to the database. Please try again shortly.',
            'timestamp' => date('Y-m-d h:i A'),
        ];

        unset($_SESSION['database_error']);

        http_response_code(503);

        $this->renderView('errors/database', [
            'error' => $error,
            'retryCooldown' => 20,
        ]);
    }

    public function unauthorizedError()
    {
        $error = $_SESSION['unauthorized_error'] ?? [
            'message' => 'Access Denied',
            'details' => "You don't have permission to access this page.",
            'timestamp' => date('Y-m-d h:i A'),
        ];

        unset($_SESSION['unauthorized_error']);
        http_response_code(403);

        $this->renderView('errors/unauthorized', ['error' => $error]);
        exit;
    }

    protected function renderView(string $viewPath, $data = []): void
    {
        extract($data);
        include __DIR__ . '/../views/' . $viewPath . '.php';
    }
}
