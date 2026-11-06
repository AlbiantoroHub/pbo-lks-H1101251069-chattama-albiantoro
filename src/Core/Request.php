<?php
namespace App\Core;

class Request
{
    public function getMethod(): string {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public function getUri(): string {
        return parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    }

    public function getBody(): array {
        $input = file_get_contents('php://input');
        return $input ? json_decode($input, true) ?? [] : [];
    }
}