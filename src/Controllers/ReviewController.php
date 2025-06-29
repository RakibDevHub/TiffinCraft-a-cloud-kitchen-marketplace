<?php
namespace App\Controllers;

use App\Core\Database;
use App\Models\Review;

class ReviewController
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function addReview()
    {
        $isLoggedIn = isset($_SESSION['user_id']) && isset($_SESSION['role']);

        if ($isLoggedIn && $_SERVER['REQUEST_METHOD'] === 'POST') {
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