<?php
namespace App\Controllers;

use Exception;
use App\Core\Database;
use App\Models\User;
use App\Models\Kitchen;
use App\Models\ServiceArea;

class HomeController
{

    // TiffinCraft 
    public function tiffincraft()
    {
        $this->renderView('buyer/home', );
    }

    // TiffinCraft Business
    public function tiffincraftBusiness()
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