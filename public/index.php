<?php
$request = $_SERVER['REQUEST_URI'];
$request = parse_url($request, PHP_URL_PATH); // removes query params

switch ($request) {
    case '/':
    case '/home':
        require '../src/views/buyer/home.php';
        break;

    case '/register':
        require '../src/views/buyer/register.php';
        break;

    case '/login':
        require '../src/views/buyer/login.php';
        break;

    case '/business/register':
        require '../src/views/seller/register.php';
        break;

    case '/business/login':
        require '../src/views/seller/login.php';
        break;

    case '/admin/dashboard':
        require '../src/views/admin/dashboard.php';
        break;

    default:
        require '../src/views/shared/404.php';
        break;
}
