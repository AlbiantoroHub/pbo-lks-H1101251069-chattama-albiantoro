<?php
namespace App\Core;

use App\Contracts\MiddlewareInterface;

class Router
{
    private array $routes = [];

    public function add(string $method, string $path, $handler, array $middlewares = []): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'middlewares' => $middlewares
        ];
    }

    public function get(string $path, $handler, array $middlewares = []): void { $this->add('GET', $path, $handler, $middlewares); }
    public function post(string $path, $handler, array $middlewares = []): void { $this->add('POST', $path, $handler, $middlewares); }
    public function put(string $path, $handler, array $middlewares = []): void { $this->add('PUT', $path, $handler, $middlewares); }
    public function delete(string $path, $handler, array $middlewares = []): void { $this->add('DELETE', $path, $handler, $middlewares); }

    public function dispatch(Request $request)
    {
        $uri = $request->getUri();
        $method = $request->getMethod();

        foreach ($this->routes as $route) {
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_]+)', $route['path']);
            $pattern = "#^" . $pattern . "$#";

            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                array_unshift($matches, $request);

                $pipeline = function ($req) use ($route, $matches) {
                    return call_user_func_array($route['handler'], $matches);
                };

                // Susun middleware secara reverse agar dieksekusi berurutan
                $middlewares = array_reverse($route['middlewares']);
                foreach ($middlewares as $middleware) {
                    $pipeline = function ($req) use ($middleware, $pipeline) {
                        return $middleware->handle($req, $pipeline);
                    };
                }

                return $pipeline($request);
            }
        }

        Response::json(['success' => false, 'message' => "Endpoint tidak ditemukan: $uri"], 404);
    }
}