<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Env;
use App\Controllers\BookController;

// Load file .env
Env::load(__DIR__ . '/../.env');

// Parse request URI dan Method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$controller = new BookController();

// Routing sederhana untuk /api/books
if ($uri === '/api/books') {
    if ($method === 'GET') {
        $controller->index();
    } elseif ($method === 'POST') {
        $controller->store();
    }
} elseif (preg_match('#^/api/books/(\d+)$#', $uri, $matches)) {
    $id = (int) $matches[1];
    if ($method === 'GET') {
        $controller->show($id);
    } elseif ($method === 'PUT' || $method === 'PATCH') {
        $controller->update($id);
    } elseif ($method === 'DELETE') {
        $controller->destroy($id);
    }
} else {
    http_response_code(404);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'status'  => 'error',
        'message' => 'Endpoint tidak ditemukan'
    ], JSON_PRETTY_PRINT);
}