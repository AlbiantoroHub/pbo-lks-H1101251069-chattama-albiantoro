<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Config\Env;
use App\Database\Connection;

Env::load(__DIR__ . '/.env');

$pdo = null;
$methodUsed = '';

// Deteksi method yang tersedia di Connection
if (method_exists(Connection::class, 'getConnection')) {
    $pdo = Connection::getConnection();
    $methodUsed = 'Connection::getConnection()';
} elseif (method_exists(Connection::class, 'getInstance')) {
    $pdo = Connection::getInstance();
    $methodUsed = 'Connection::getInstance()';
} elseif (method_exists(Connection::class, 'get')) {
    $pdo = Connection::get();
    $methodUsed = 'Connection::get()';
} else {
    // Cadangan koneksi langsung menggunakan konfigurasi .env
    $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
    $port = $_ENV['DB_PORT'] ?? '3306';
    $db   = $_ENV['DB_DATABASE'] ?? 'perpustakaan';
    $user = $_ENV['DB_USERNAME'] ?? 'root';
    $pass = $_ENV['DB_PASSWORD'] ?? '';

    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $methodUsed = 'PDO Direct Fallback';
}

// 1. Buat tabel categories jika belum ada
$pdo->exec("
    CREATE TABLE IF NOT EXISTS categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(100) NOT NULL,
        deskripsi TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );
");

// 2. Bersihkan dan isi 2 data awal
$pdo->exec("DELETE FROM categories");
$pdo->exec("
    INSERT INTO categories (nama, deskripsi) VALUES 
    ('Fiksi', 'Buku-buku fiksi dan novel'), 
    ('Non-Fiksi', 'Buku non-fiksi, sains, sejarah');
");

// 3. Tampilkan isi tabel categories
$stmt = $pdo->query("SELECT id, nama, deskripsi FROM categories");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Tabel categories berhasil dibuat dan diisi:\n";
echo "--------------------------------------------------------\n";
printf("%-5s | %-15s | %s\n", "ID", "Nama", "Deskripsi");
echo "--------------------------------------------------------\n";
foreach ($rows as $row) {
    printf("%-5s | %-15s | %s\n", $row['id'], $row['nama'], $row['deskripsi']);
}
echo "--------------------------------------------------------\n";