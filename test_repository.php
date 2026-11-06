<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Config\Env;
use App\Repositories\MySQLBookRepository;

Env::load(__DIR__ . '/.env');

$repo = new MySQLBookRepository();
$books = $repo->getAll();

echo "Total buku: " . count($books) . "\n";
foreach ($books as $book) {
    echo $book->getJudul() . " oleh " . $book->getPenulis() . "\n";
}