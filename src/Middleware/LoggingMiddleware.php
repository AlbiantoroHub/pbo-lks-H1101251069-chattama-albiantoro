<?php
namespace App\Middleware;

use App\Contracts\MiddlewareInterface;
use App\Core\Request;
use App\Core\Logger;

class LoggingMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next)
    {
        $method = $request->getMethod();
        $uri = $request->getUri();

        Logger::info("Request Masuk: $method $uri");

        $response = $next($request);

        Logger::info("Request Selesai: $method $uri");

        return $response;
    }
}