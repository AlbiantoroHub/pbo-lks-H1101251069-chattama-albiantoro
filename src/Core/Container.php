<?php
namespace App\Core;

use Exception;

class Container
{
    private array $bindings = [];

    public function bind(string $key, callable $resolver): void {
        $this->bindings[$key] = $resolver;
    }

    public function make(string $key) {
        if (!isset($this->bindings[$key])) {
            throw new Exception("Tidak ada binding untuk {$key}");
        }
        return call_user_func($this->bindings[$key], $this);
    }
}