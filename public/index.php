<?php
// public/index.php 

session_start();

require_once __DIR__ . '/../src/core/Router.php';
require_once __DIR__ . '/../src/core/Database.php';
require_once __DIR__ . '/../src/controllers/AuthController.php';
require_once __DIR__ . '/../src/controllers/PageController.php';

require_once __DIR__ . '/../src/Models/User.php';
require_once __DIR__ . '/../src/Models/Kitchen.php';
require_once __DIR__ . '/../src/Models/ServiceArea.php';

use App\Core\Router;

$router = new Router();

$router->addRoute('/', 'PageController@buyer');
$router->addRoute('/home', 'PageController@buyer');
$router->addRoute('/business', 'PageController@seller');


$router->addRoute('/register', 'AuthController@registerAsBuyer');
$router->addRoute('/business/register', 'AuthController@registerAsSeller');

$router->addRoute('/login', 'AuthController@login');
$router->addRoute('/logout', 'AuthController@logout');

$router->addRoute('/dashboard', 'PageController@dashboard');
$router->addRoute('/business/dashboard', 'PageController@businessDashboard');
$router->addRoute('/admin', 'PageController@adminDashboard');
$router->addRoute('/admin/dashboard', 'PageController@adminDashboard');
$router->addRoute('/admin/users', 'PageController@manageUsers');
$router->addRoute('/admin/orders', 'PageController@manageOrders');
$router->addRoute('/admin/reports', 'PageController@manageReports');
$router->addRoute('/admin/dishes', 'PageController@manageDishes');
$router->addRoute('/admin/kitchens', 'PageController@manageKitchens');

// In your router configuration
$router->dispatch($_SERVER['REQUEST_URI']);