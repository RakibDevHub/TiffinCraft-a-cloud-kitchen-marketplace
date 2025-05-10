<?php
namespace App\Controllers;

class HomeController
{
    public function buyer()
    {
        $this->renderView('buyer/home');

    }

    public function seller()
    {
        $this->renderView('seller/home');

    }

    protected function renderView(string $viewPath): void
    {
        include __DIR__ . '/../views/' . $viewPath . '.php';
    }
}
