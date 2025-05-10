<?php
// public/index.php 

session_start();

require_once __DIR__ . '/../src/core/Router.php';
require_once __DIR__ . '/../src/core/Database.php';
require_once __DIR__ . '/../src/controllers/HomeController.php';
require_once __DIR__ . '/../src/controllers/AuthController.php';
require_once __DIR__ . '/../src/controllers/UserController.php';

require_once __DIR__ . '/../src/Models/User.php';
require_once __DIR__ . '/../src/Models/Kitchen.php';
require_once __DIR__ . '/../src/Models/ServiceArea.php';

use App\Core\Router;

$router = new Router();

$router->addRoute('/', 'HomeController@buyer');
$router->addRoute('/home', 'HomeController@buyer');
$router->addRoute('/login', 'AuthController@login');
$router->addRoute('/register', 'AuthController@registerAsBuyer');
$router->addRoute('/business', 'HomeController@seller');
$router->addRoute('/business/register', 'AuthController@registerAsSeller');
$router->addRoute('/logout', 'AuthController@logout');

$router->addRoute('/dashboard', 'UserController@dashboard');
$router->addRoute('/business/dashboard', 'UserController@businessDashboard');
$router->addRoute('/admin', 'UserController@adminDashboard');
$router->addRoute('/admin/dashboard', 'UserController@adminDashboard');

// In your router configuration
$router->addRoute('/test/rollback', 'AuthController@testRollback');

$router->dispatch($_SERVER['REQUEST_URI']);