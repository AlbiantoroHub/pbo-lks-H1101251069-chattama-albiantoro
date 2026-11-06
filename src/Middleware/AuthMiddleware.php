<?php
namespace App\Middleware;

use App\Contracts\MiddlewareInterface;
use App\Core\Request;
use App\Core\Response;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next)
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? null;

        // Validasi token statis sederhana
        if (!$authHeader || $authHeader !== 'Bearer token-rahasia-pbo') {
            Response::json([
                'success' => false,
                'message' => 'Akses ditolak: Token otentikasi tidak valid atau tidak disertakan'
            ], 401);
        }

        return $next($request);
    }
}