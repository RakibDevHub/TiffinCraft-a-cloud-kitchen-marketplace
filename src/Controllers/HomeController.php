<?php
namespace App\Controllers;

use App\Models\Menu;
use Exception;
use App\Core\Database;
use App\Models\Category;
use App\Models\User;
use App\Models\Kitchen;
use App\Models\ServiceArea;
use App\Models\Review;

class HomeController
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    // TiffinCraft 
    public function showBuyerHome()
    {
        try {
            $categories = Category::getAllCategories($this->conn);
            $kitchenType = 'top';
            $result = Kitchen::getKitchensForHomePage($this->conn, $kitchenType, 10);
            $reviews = Review::getPlatFormReviews($this->conn);

            if (empty($result['kitchens']) || !$result['hasRatings']) {
                $kitchenType = 'newest';
                $result = Kitchen::getKitchensForHomePage($this->conn, $kitchenType, 10);
            }

            $this->renderView('buyer/home', [
                'pageTitle' => 'TiffinCraft - Home',
                'categories' => $categories ?? [],
                'kitchens' => $result['kitchens'] ?? [],
                'hasRatings' => $result['hasRatings'] ?? false,
                'platform_reviews' => $reviews ?? [],
                'error' => null
            ]);

        } catch (Exception $e) {
            error_log('Failed to load home data: ' . $e->getMessage());

            $this->renderView('buyer/home', [
                'pageTitle' => 'TiffinCraft - Home',
                'categories' => [],
                'kitchens' => [],
                'hasRatings' => false,
                'platform_reviews' => [],
                'error' => 'Failed to load categories. Please try again later.'
            ]);
        }
    }

    // TiffinCraft Business
    public function showSellerHome()
    {
        $this->renderView('seller/home');
    }

    protected function renderView(string $viewPath, $data = []): void
    {
        extract($data);
        include __DIR__ . '/../views/' . $viewPath . '.php';
    }

}
?>