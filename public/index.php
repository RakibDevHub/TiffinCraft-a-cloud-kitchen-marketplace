<?php

use App\Controllers\HomeController;
// public/index.php 

session_start();

define('BASE_PATH', dirname(__DIR__, 1));
require_once BASE_PATH . '/src/utils/helper.php';
require_once BASE_PATH . '/src/utils/session_helper.php';

require_once BASE_PATH . '/src/core/Router.php';
require_once BASE_PATH . '/src/core/Database.php';

require_once BASE_PATH . '/src/controllers/HomeController.php';
require_once BASE_PATH . '/src/controllers/DashboardController.php';
require_once BASE_PATH . '/src/controllers/UserController.php';
require_once BASE_PATH . '/src/controllers/KitchenController.php';
require_once BASE_PATH . '/src/controllers/AuthController.php';
require_once BASE_PATH . '/src/controllers/MenuController.php';
require_once BASE_PATH . '/src/controllers/ErrorController.php';
require_once BASE_PATH . '/src/controllers/CategoryController.php';
require_once BASE_PATH . '/src/controllers/ReviewController.php';

require_once BASE_PATH . '/src/Models/UserModel.php';
require_once BASE_PATH . '/src/Models/KitchenModel.php';
require_once BASE_PATH . '/src/Models/ServiceAreaModel.php';
require_once BASE_PATH . '/src/Models/MenuModel.php';
require_once BASE_PATH . '/src/Models/CategoryModel.php';
require_once BASE_PATH . '/src/Models/ReviewModel.php';

use App\Core\Router;
use App\Utils\SessionHelper;

SessionHelper::refreshUserSession();

$router = new Router();

// Database Error
$router->addRoute('/database-error', 'ErrorController@databaseError');
$router->addRoute('/unauthorized', 'ErrorController@unauthorizedError');

// Public Routes
$router->addRoute('/', 'HomeController@landingPage');
$router->addRoute('/home', 'HomeController@landingPage');
$router->addRoute('/dishes', 'MenuController@MenuItemPage');
$router->addRoute('/reviews', 'ReviewController@addReview');
$router->addRoute('/kitchens', 'KitchenController@kitchenPage');
$router->addRoute('/kitchen/profile', 'KitchenController@showKitchenProfile');
$router->addRoute('/contact', 'HomeController@showContactPage');

$router->addRoute('/business', 'HomeController@businessPage');

// Authentication Routes
$router->addRoute('/register', 'AuthController@registerAsBuyer');
$router->addRoute('/business/register', 'AuthController@registerAsSeller');
$router->addRoute('/login', 'AuthController@login');
$router->addRoute('/business/login', 'AuthController@login');
$router->addRoute('/logout', 'AuthController@logout');

// Dashboard Routes
$router->addRoute('/dashboard', 'DashboardController@dashboard');
$router->addRoute('/business/dashboard', 'DashboardController@businessDashboard');

// Admin Routes
$router->addRoute('/admin', 'DashboardController@adminDashboard');
$router->addRoute('/admin/dashboard', 'DashboardController@adminDashboard');

$router->addRoute('/admin/dashboard/reviews', 'ReviewController@manageReviews');

$router->addRoute('/admin/dashboard/users', 'UserController@manageUsers');
$router->addRoute('/admin/dashboard/users/activate/{id}', 'UserController@activateUser');
$router->addRoute('/admin/dashboard/users/deactivate/{id}', 'UserController@deactivateUser');
$router->addRoute('/admin/dashboard/users/suspend/{id}', 'UserController@suspendUser');

$router->addRoute('/admin/dashboard/categories', 'CategoryController@manageCategories');
$router->addRoute('/admin/dashboard/categories/add', 'CategoryController@addCategory');
$router->addRoute('/admin/dashboard/categories/edit/{id}', 'CategoryController@editCategory');
$router->addRoute('/admin/dashboard/categories/delete/{id}', 'CategoryController@deleteCategory');

$router->addRoute('/admin/dashboard/orders', 'PageController@manageOrders');
$router->addRoute('/admin/dashboard/reports', 'PageController@manageReports');
$router->addRoute('/admin/dashboard/dishes', 'PageController@manageDishes');

// Kitchen Management Routes
$router->addRoute('/admin/dashboard/kitchens', 'KitchenController@manageKitchens');
$router->addRoute('/admin/dashboard/kitchens/approve/{id}', 'KitchenController@approveKitchen');
$router->addRoute('/admin/dashboard/kitchens/reject/{id}', 'KitchenController@rejectKitchen');
$router->addRoute('/admin/dashboard/kitchens/suspend/{id}', 'KitchenController@suspendKitchen');

// Business Owner Kitchen Routes
$router->addRoute('/business/dashboard/menu', 'MenuController@manageMenuPage');
$router->addRoute('/business/dashboard/menu/add', 'MenuController@addMenuItem');
$router->addRoute('/business/dashboard/menu/edit/{id}', 'MenuController@editMenuItem');
$router->addRoute('/business/dashboard/menu/delete/{id}', 'MenuController@deleteMenuItem');

$router->addRoute('/business/kitchens', 'PageController@businessKitchens');
$router->addRoute('/business/kitchens/create', 'PageController@businessCreateKitchen');
$router->addRoute('/business/kitchens/edit/{id}', 'PageController@businessEditKitchen');

// Dispatch the router
$router->dispatch($_SERVER['REQUEST_URI']);