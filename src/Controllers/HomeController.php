<?php
namespace App\Controllers;

use Exception;
use App\Models\Category;
use App\Models\Kitchen;
use App\Models\Review;

class HomeController extends BaseController
{
    // TiffinCraft 
    public function landingPage()
    {
        try {
            $conn = $this->db();

            $categories = Category::getAllCategories($conn);
            $kitchenType = 'top';
            $result = Kitchen::getKitchensForHomePage($conn, $kitchenType, 10);
            $reviews = Review::getPlatFormReviews($conn);

            if (empty($result['kitchens']) || !$result['hasRatings']) {
                $kitchenType = 'newest';
                $result = Kitchen::getKitchensForHomePage($conn, $kitchenType, 10);
            }

            $this->renderView('pages/landing', [
                'pageTitle' => 'TiffinCraft - Home',
                'categories' => $categories ?? [],
                'kitchens' => $result['kitchens'] ?? [],
                'hasRatings' => $result['hasRatings'] ?? false,
                'platform_reviews' => $reviews ?? [],
                'error' => null
            ]);
        } catch (Exception $e) {
            error_log('Failed to load home data: ' . $e->getMessage());

            $this->renderView('pages/landing', [
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
    public function businessPage()
    {
        $this->renderView('pages/business');
    }

    public function showContactPage()
    {
        $this->renderView('pages/contact', [
            'error' => $this->getFlash('error'),
            'success' => $this->getFlash('success')
        ]);
    }

    protected function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'][$type] = $message;
    }

    protected function getFlash(string $type): ?string
    {
        $message = $_SESSION['flash'][$type] ?? null;
        unset($_SESSION['flash'][$type]);
        return $message;
    }

    protected function renderView(string $viewPath, $data = []): void
    {
        extract($data);
        include __DIR__ . '/../views/' . $viewPath . '.php';
    }
}
