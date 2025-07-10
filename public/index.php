<?php
session_start();
date_default_timezone_set('Asia/Dhaka');

// Define base path
define('BASE_PATH', dirname(__DIR__, 1));

// Utilities
require_once BASE_PATH . '/src/utils/helper.php';
require_once BASE_PATH . '/src/utils/session_helper.php';

// Core
require_once BASE_PATH . '/src/core/Router.php';
require_once BASE_PATH . '/src/core/Database.php';

// Controllers
require_once BASE_PATH . '/src/controllers/BaseController.php';
require_once BASE_PATH . '/src/controllers/HomeController.php';
require_once BASE_PATH . '/src/controllers/AuthController.php';
require_once BASE_PATH . '/src/controllers/UserController.php';
require_once BASE_PATH . '/src/controllers/KitchenController.php';
require_once BASE_PATH . '/src/controllers/MenuController.php';
require_once BASE_PATH . '/src/controllers/ReviewController.php';
require_once BASE_PATH . '/src/controllers/DashboardController.php';
require_once BASE_PATH . '/src/controllers/ErrorController.php';
require_once BASE_PATH . '/src/controllers/CategoryController.php';
// require_once BASE_PATH . '/src/controllers/PageController.php';

// Models
require_once BASE_PATH . '/src/Models/UserModel.php';
require_once BASE_PATH . '/src/Models/KitchenModel.php';
require_once BASE_PATH . '/src/Models/ServiceAreaModel.php';
require_once BASE_PATH . '/src/Models/MenuModel.php';
require_once BASE_PATH . '/src/Models/CategoryModel.php';
require_once BASE_PATH . '/src/Models/ReviewModel.php';

use App\Core\Router;
use App\Utils\SessionHelper;

// Refresh session
SessionHelper::refreshUserSession();

// Initialize router
$router = new Router();

// Error Routes
$router->addRoute('/database-error', 'ErrorController@databaseError');
$router->addRoute('/unauthorized', 'ErrorController@unauthorizedError');
$router->addRoute('/404', 'ErrorController@databaseError');

// Public Routes
$router->addRoute('/', 'HomeController@landingPage');
$router->addRoute('/home', 'HomeController@landingPage');
$router->addRoute('/contact', 'HomeController@showContactPage');
$router->addRoute('/business', 'HomeController@businessPage');

$router->addRoute('/dishes', 'MenuController@MenuItemPage');
$router->addRoute('/reviews', 'ReviewController@addReview');
$router->addRoute('/kitchens', 'KitchenController@kitchenPage');
$router->addRoute('/kitchen/profile', 'KitchenController@showKitchenProfile');

// Authentication Routes
$router->addRoute('/login', 'AuthController@login');
$router->addRoute('/register', 'AuthController@registerAsBuyer');
$router->addRoute('/business/login', 'AuthController@login');
$router->addRoute('/business/register', 'AuthController@registerAsSeller');
$router->addRoute('/logout', 'AuthController@logout');

// Dashboard Routes
$router->addRoute('/dashboard', 'DashboardController@dashboard');
$router->addRoute('/business/dashboard', 'DashboardController@businessDashboard');

// Admin Dashboard Routes
$router->addRoute('/admin', 'DashboardController@adminDashboard');
$router->addRoute('/admin/dashboard', 'DashboardController@adminDashboard');

// Admin: User Management
$router->addRoute('/admin/dashboard/users', 'UserController@manageUsers');
$router->addRoute('/admin/dashboard/users/activate/{id}', 'UserController@activateUser');
$router->addRoute('/admin/dashboard/users/deactivate/{id}', 'UserController@deactivateUser');
$router->addRoute('/admin/dashboard/users/suspend/{id}', 'UserController@suspendUser');

// Admin: Category Management
$router->addRoute('/admin/dashboard/categories', 'CategoryController@manageCategories');
$router->addRoute('/admin/dashboard/categories/add', 'CategoryController@addCategory');
$router->addRoute('/admin/dashboard/categories/edit/{id}', 'CategoryController@editCategory');
$router->addRoute('/admin/dashboard/categories/delete/{id}', 'CategoryController@deleteCategory');

// Admin: Kitchen Management
$router->addRoute('/admin/dashboard/kitchens', 'KitchenController@manageKitchens');
$router->addRoute('/admin/dashboard/kitchens/approve/{id}', 'KitchenController@approveKitchen');
$router->addRoute('/admin/dashboard/kitchens/reject/{id}', 'KitchenController@rejectKitchen');
$router->addRoute('/admin/dashboard/kitchens/suspend/{id}', 'KitchenController@suspendKitchen');

// Admin: Other Pages
$router->addRoute('/admin/dashboard/orders', 'PageController@manageOrders');
$router->addRoute('/admin/dashboard/reports', 'PageController@manageReports');
$router->addRoute('/admin/dashboard/dishes', 'PageController@manageDishes');
$router->addRoute('/admin/dashboard/reviews', 'ReviewController@manageReviews');

// Business(Seller): Kitchen Menu
$router->addRoute('/business/dashboard/menu', 'MenuController@manageMenuPage');
$router->addRoute('/business/dashboard/menu/add', 'MenuController@addMenuItem');
$router->addRoute('/business/dashboard/menu/edit/{id}', 'MenuController@editMenuItem');
$router->addRoute('/business/dashboard/menu/delete/{id}', 'MenuController@deleteMenuItem');

// Business(Seller): Kitchen Management
$router->addRoute('/business/kitchens', 'PageController@businessKitchens');
$router->addRoute('/business/kitchens/create', 'PageController@businessCreateKitchen');
$router->addRoute('/business/kitchens/edit/{id}', 'PageController@businessEditKitchen');

// Dispatch
$router->dispatch($_SERVER['REQUEST_URI']);
