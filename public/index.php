<?php
// public/index.php 

session_start();

define('BASE_PATH', dirname(__DIR__, 1));
require_once BASE_PATH . '/src/utils/helper.php';

require_once BASE_PATH . '/src/core/Router.php';
require_once BASE_PATH . '/src/core/Database.php';

require_once BASE_PATH . '/src/controllers/HomeController.php';
require_once BASE_PATH . '/src/controllers/DashboardController.php';
require_once BASE_PATH . '/src/controllers/UserController.php';
require_once BASE_PATH . '/src/controllers/KitchenController.php';
require_once BASE_PATH . '/src/controllers/AuthController.php';
require_once BASE_PATH . '/src/controllers/PageController.php';

require_once BASE_PATH . '/src/Models/User.php';
require_once BASE_PATH . '/src/Models/Kitchen.php';
require_once BASE_PATH . '/src/Models/ServiceArea.php';

use App\Core\Router;

$router = new Router();

// Public Routes
$router->addRoute('/', 'HomeController@tiffincraft');
$router->addRoute('/home', 'HomeController@tiffincraft');
$router->addRoute('/business', 'HomeController@tiffincraftBusiness');

// Authentication Routes
$router->addRoute('/register', 'AuthController@registerAsBuyer');
$router->addRoute('/business/register', 'AuthController@registerAsSeller');
$router->addRoute('/login', 'AuthController@login');
$router->addRoute('/logout', 'AuthController@logout');

// Dashboard Routes
$router->addRoute('/dashboard', 'DashboardController@dashboard');
$router->addRoute('/business/dashboard', 'DashboardController@businessDashboard');

// Admin Routes
$router->addRoute('/admin', 'DashboardController@adminDashboard');
$router->addRoute('/admin/dashboard', 'DashboardController@adminDashboard');
$router->addRoute('/admin/users', 'UserController@manageUsers');
$router->addRoute('/admin/orders', 'PageController@manageOrders');
$router->addRoute('/admin/reports', 'PageController@manageReports');
$router->addRoute('/admin/dishes', 'PageController@manageDishes');

// Kitchen Management Routes
$router->addRoute('/admin/kitchens', 'KitchenController@fetchKitchens');
$router->addRoute('/admin/kitchens/approve/{id}', 'KitchenController@approveKitchen');
$router->addRoute('/admin/kitchens/reject/{id}', 'KitchenController@rejectKitchen');
$router->addRoute('/admin/kitchens/edit/{id}', 'PageController@editKitchen');
$router->addRoute('/admin/kitchens/view/{id}', 'PageController@viewKitchen');

// Business Owner Kitchen Routes
$router->addRoute('/business/kitchens', 'PageController@businessKitchens');
$router->addRoute('/business/kitchens/create', 'PageController@businessCreateKitchen');
$router->addRoute('/business/kitchens/edit/{id}', 'PageController@businessEditKitchen');

// Dispatch the router
$router->dispatch($_SERVER['REQUEST_URI']);