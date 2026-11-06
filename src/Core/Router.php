<?php
namespace App\Core;

class Router
{
    private array $routes = [];

    public function add(string $method, string $path, $handler): void {
        $this->routes[] = ['method' => $method, 'path' => $path, 'handler' => $handler];
    }

    public function get(string $path, $handler): void { $this->add('GET', $path, $handler); }
    public function post(string $path, $handler): void { $this->add('POST', $path, $handler); }
    public function put(string $path, $handler): void { $this->add('PUT', $path, $handler); }
    public function delete(string $path, $handler): void { $this->add('DELETE', $path, $handler); }

    public function dispatch(Request $request) {
        $uri = $request->getUri();
        $method = $request->getMethod();

        foreach ($this->routes as $route) {
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_]+)', $route['path']);
            $pattern = "#^" . $pattern . "$#";

            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                return call_user_func_array($route['handler'], $matches);
            }
        }

        Response::json(['success' => false, 'message' => "Endpoint tidak ditemukan: $uri"], 404);
    }
}