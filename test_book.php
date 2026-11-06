<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Config\Env;
use App\Models\Book;

// Load konfigurasi database
Env::load(__DIR__ . '/.env');

try {
    $bookModel = new Book();
    $books = $bookModel->getAll();

    echo "=== Daftar Buku ===\n";
    foreach ($books as $book) {
        echo "ID: {$book['id']} | Judul: {$book['judul']} | Penulis: {$book['penulis']}\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}