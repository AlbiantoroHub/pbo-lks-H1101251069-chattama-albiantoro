<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Config\Env;
use App\Repositories\MySQLBookRepository;
use App\Services\BookService;

Env::load(__DIR__ . '/.env');

$repo = new MySQLBookRepository();
$service = new BookService($repo);

// Test 1: Hitung total buku
$books = $service->getAllBooks();
echo "Daftar buku: " . count($books) . "\n";

// Test 2: Uji validasi input kosong
try {
    $service->createBook(['judul' => '', 'penulis' => 'Test', 'tahun' => 2024]);
} catch (\Exception $e) {
    echo "Validasi berjalan: " . $e->getMessage() . "\n";
}

// Test 3: Uji tambah buku yang valid
try {
    $newId = $service->createBook([
        'judul' => 'Pulang',
        'penulis' => 'Tere Liye',
        'tahun' => 2015,
        'stok' => 10
    ]);
    echo "Buku dibuat: Pulang (ID: $newId)\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}