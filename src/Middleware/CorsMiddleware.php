<?php
namespace App\Middleware;

use App\Contracts\MiddlewareInterface;
use App\Core\Request;

class CorsMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

        if ($request->getMethod() === 'OPTIONS') {
            http_response_code(200);
            exit;
        }

        return $next($request);
    }
}