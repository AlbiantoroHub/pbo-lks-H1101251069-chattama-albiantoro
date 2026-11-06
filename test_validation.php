<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Validator;
use App\Exceptions\ValidationException;

$rules = [
    'judul' => 'required',
    'penulis' => 'required',
    'tahun' => 'required|numeric|min:1900',
    'stok' => 'numeric|min:0'
];

// Data uji coba yang sengaja dibuat salah
$invalidData = [
    'judul' => '',
    'penulis' => 'Penulis Uji',
    'tahun' => 'seribu sembilan ratus',
    'stok' => -5
];

try {
    Validator::validate($invalidData, $rules);
    echo "Validasi berhasil (tidak terduga)!\n";
} catch (ValidationException $e) {
    echo "Pesan: " . $e->getMessage() . "\n";
    echo "Daftar Error:\n";
    foreach ($e->getErrors() as $field => $messages) {
        foreach ($messages as $msg) {
            echo "- [$field]: $msg\n";
        }
    }
}