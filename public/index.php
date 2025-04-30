<?php
session_start();

require_once __DIR__ . '/../src/core/Router.php';
require_once __DIR__ . '/../src/controllers/HomeController.php';
require_once __DIR__ . '/../src/controllers/AuthController.php';

use App\Core\Router;

$router = new Router();

$router->addRoute('/', 'HomeController@index');
$router->addRoute('/login', 'AuthController@login');
$router->addRoute('/register', 'AuthController@register');

$router->dispatch($_SERVER['REQUEST_URI']);


// $request = $_SERVER['REQUEST_URI'];

// switch ($request) {

//     case '/register_buyer':
//     case '/register_buyer.php':
//         require '../src/controllers/register_buyer.php';
//         break;

//     case '/':
//     case '/home':
//     case '/index':
//     case '/index.php':
//         require '../src/views/buyer/home.php';
//         break;
//     case '/register':
//     case '/register.php':
//         require '../src/views/buyer/register.php';
//         break;
//     case '/business/register':
//     case '/business/register.php':
//         require '../src/views/seller/register.php';
//         break;
//     case '/login':
//         require '../src/views/auth/login.php';
//         break;
//     default:
//         http_response_code(404);
//         require '../src/views/shared/404.php';
//         break;
// }
