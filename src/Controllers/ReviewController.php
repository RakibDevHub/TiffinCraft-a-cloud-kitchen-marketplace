<?php
namespace App\Controllers;

use App\Core\Database;
use App\Models\Review;
use Exception;

class ReviewController
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function manageReviews()
    {
        $this->requireLogin('admin');

        try {

            $this->renderView('admin/reviews', [
                'reviews' => Review::getAllReviews($this->conn),
                'success' => $this->getFlash('success'),
                'error' => $this->getFlash('error'),
            ]);
        } catch (Exception $e) {
            error_log('Failed to load reviews: ' . $e->getMessage());
            $this->renderView('admin/reviews', [
                'error' => 'Failed to load reviews: ' . $e->getMessage(),
                'reviews' => []
            ]);
        }

    }

    public function addReview()
    {
        $this->requireLogin(['seller', 'buyer']);
        $this->validateCsrf();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $role = $_SESSION['role'];
            $rating = intval($_POST['rating'] ?? 0);
            $comments = trim($_POST['comments'] ?? '');

            if (($role === 'buyer' || $role === 'seller') && $rating >= 1 && $rating <= 5 && $comments !== '') {
                if (Review::hasUserReviewed($this->conn, $userId)) {
                    $_SESSION['toast'] = ['type' => 'error', 'message' => 'You’ve already submitted a review.'];
                } else {
                    $success = Review::submitReview($this->conn, $userId, $rating, $comments);
                    $_SESSION['toast'] = $success
                        ? ['type' => 'success', 'message' => 'Thanks for your feedback!']
                        : ['type' => 'error', 'message' => 'Failed to submit review. Try again.'];
                }
            } else {
                $_SESSION['toast'] = ['type' => 'error', 'message' => 'Invalid review data.'];
            }

            $this->redirect('/#testimonials');
        }

    }

    public function updateReviewStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['review_id'] ?? null;
            $newStatus = $_POST['status'] ?? null;
            if ($id && in_array($newStatus, ['pending', 'active', 'hidden'])) {
                $result = Review::updateStatus($this->conn, $id, $newStatus);
                $_SESSION['toast']['message'] = $result ? "Review updated." : "Failed to update review.";
            }
            $this->redirect('/admin/reviews');
        }
    }


    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']) && isset($_SESSION['role']);
    }

    protected function requireLogin($requiredRoles = null): void
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
            exit;
        }

        if ($requiredRoles) {
            if (is_array($requiredRoles)) {
                if (!in_array($_SESSION['role'], $requiredRoles)) {
                    $this->redirect('/unauthorized');
                    exit;
                }
            } else {
                if ($_SESSION['role'] !== $requiredRoles) {
                    $this->redirect('/unauthorized');
                    exit;
                }
            }
        }
    }

    protected function validateCsrf(): void
    {
        if (
            $_SERVER['REQUEST_METHOD'] !== 'POST' ||
            !isset($_POST['csrf_token']) ||
            !Helper::validateCsrfToken($_POST['csrf_token'])
        ) {
            $this->setFlash('error', "Invalid or missing CSRF token.");
            $this->redirect("/admin/categories");
        }
    }

    protected function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'][$type] = $message;
    }

    protected function getFlash(string $type): ?string
    {
        if (isset($_SESSION['flash'][$type])) {
            $message = $_SESSION['flash'][$type];
            unset($_SESSION['flash'][$type]);
            return $message;
        }
        return null;
    }

    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    protected function renderView(string $viewPath, array $data = []): void
    {
        extract($data);
        include __DIR__ . '/../views/' . $viewPath . '.php';
    }
}

?>