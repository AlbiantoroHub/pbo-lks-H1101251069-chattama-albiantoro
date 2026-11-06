<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Request;
use App\Core\Router;

$router = new Router();
$router->get('/api/hello', function() {
    echo "Router bekerja! Endpoint /api/hello terpanggil.\n";
});

// Simulasi Request
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/api/hello';

$request = new Request();
$router->dispatch($request);