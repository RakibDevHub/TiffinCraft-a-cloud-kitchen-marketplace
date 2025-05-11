<?php
namespace App\Controllers;

class HomeController
{
    public function buyer()
    {
        $this->renderView('index');

    }

    public function seller()
    {
        $this->renderView('index');

    }

    protected function renderView(string $viewPath): void
    {
        include __DIR__ . '/../views/' . $viewPath . '.php';
    }
}
