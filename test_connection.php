<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Config\Env;
use App\Database\Connection;

Env::load(__DIR__ . '/.env');

try {
    $pdo = Connection::get();
    echo "Koneksi database berhasil!\n";
    echo "Versi MySQL: " . $pdo->query('select version()')->fetchColumn() . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}