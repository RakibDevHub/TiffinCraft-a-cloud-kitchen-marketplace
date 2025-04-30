<?php
session_start();


require_once __DIR__ . '/../src/core/Router.php';
require_once __DIR__ . '/../src/core/Database.php';
require_once __DIR__ . '/../src/controllers/HomeController.php';
require_once __DIR__ . '/../src/controllers/AuthController.php';

require_once __DIR__ . '/../src/Models/User.php';

use App\Core\Router;

$router = new Router();

$router->addRoute('/', 'HomeController@index');
$router->addRoute('/login', 'AuthController@login');
$router->addRoute('/register', 'AuthController@registerAsBuyer');
$router->addRoute('/business/register', 'AuthController@registerAsSeller');
$router->addRoute('/logout', 'AuthController@logout');

$router->dispatch($_SERVER['REQUEST_URI']);