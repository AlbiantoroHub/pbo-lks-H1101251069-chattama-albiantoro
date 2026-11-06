<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Env;
use App\Core\Container;
use App\Core\Request;
use App\Core\Router;
use App\Repositories\MySQLBookRepository;
use App\Contracts\BookRepositoryInterface;
use App\Services\BookService;
use App\Controllers\BookController;

// 1. Load Environment
Env::load(__DIR__ . '/../.env');

// 2. Setup Container (Dependency Injection)
$container = new Container();

$container->bind(BookRepositoryInterface::class, function() {
    return new MySQLBookRepository();
});

$container->bind(BookService::class, function($c) {
    return new BookService($c->make(BookRepositoryInterface::class));
});

$container->bind(BookController::class, function($c) {
    return new BookController($c->make(BookService::class));
});

// 3. Setup Router
$router = new Router();
$controller = $container->make(BookController::class);

$router->get('/api/books', [$controller, 'index']);
$router->get('/api/books/{id}', [$controller, 'show']);
$router->post('/api/books', [$controller, 'store']);
$router->put('/api/books/{id}', [$controller, 'update']);
$router->delete('/api/books/{id}', [$controller, 'destroy']);

// 4. Dispatch Request
$request = new Request();
$router->dispatch($request);