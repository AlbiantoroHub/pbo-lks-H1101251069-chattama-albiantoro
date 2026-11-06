<?php
// Header CORS untuk mengizinkan akses dari frontend
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');
header('Access-Control-Max-Age: 86400');

// Tangani preflight request dari browser
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Env;
use App\Core\Container;
use App\Core\Request;
use App\Core\Router;

// Import Book
use App\Contracts\BookRepositoryInterface;
use App\Repositories\MySQLBookRepository;
use App\Services\BookService;
use App\Controllers\BookController;

// Import Category
use App\Contracts\CategoryRepositoryInterface;
use App\Repositories\MySQLCategoryRepository;
use App\Services\CategoryService;
use App\Controllers\CategoryController;

Env::load(__DIR__ . '/../.env');

$request = new Request();
$container = new Container();

// Binding Book
$container->bind(BookRepositoryInterface::class, function() {
    return new MySQLBookRepository();
});
$container->bind(BookService::class, function($c) {
    return new BookService($c->make(BookRepositoryInterface::class));
});
$container->bind(BookController::class, function($c) {
    return new BookController($c->make(BookService::class));
});

// Binding Category
$container->bind(CategoryRepositoryInterface::class, function() {
    return new MySQLCategoryRepository();
});
$container->bind(CategoryService::class, function($c) {
    return new CategoryService($c->make(CategoryRepositoryInterface::class));
});
$container->bind(CategoryController::class, function($c) {
    return new CategoryController($c->make(CategoryService::class));
});

// Setup Router
$router = new Router();
$bookController = $container->make(BookController::class);
$categoryController = $container->make(CategoryController::class);

// Route Books
$router->get('/api/books', [$bookController, 'index']);
$router->get('/api/books/{id}', [$bookController, 'show']);
$router->post('/api/books', [$bookController, 'store']);
$router->put('/api/books/{id}', [$bookController, 'update']);
$router->delete('/api/books/{id}', [$bookController, 'destroy']);

// Route Categories
$router->get('/api/categories', [$categoryController, 'index']);
$router->get('/api/categories/{id}', [$categoryController, 'show']);
$router->post('/api/categories', [$categoryController, 'store']);
$router->put('/api/categories/{id}', [$categoryController, 'update']);
$router->delete('/api/categories/{id}', [$categoryController, 'destroy']);

// Dispatch
$router->dispatch($request);