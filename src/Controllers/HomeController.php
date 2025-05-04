<?php
namespace App\Controllers;

class HomeController
{
    public function index()
    {
        $this->renderView('buyer/home');

    }

    protected function renderView(string $viewPath): void
    {
        include __DIR__ . '/../views/' . $viewPath . '.php';
    }
}
