<?php
session_start();

$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '/':
    case '/home':
    case '/index':
    case '/index.php':
        require '../src/views/buyer/home.php';
        break;
    case '/register':
    case '/register.php':
        require '../src/views/buyer/register.php';
        break;
    case '/business/register':
    case '/business/register.php':
        require '../src/views/seller/register.php';
        break;
    case '/login':
        require '../src/views/auth/login.php';
        break;
    default:
        http_response_code(404);
        require '../src/views/shared/404.php';
        break;
}
