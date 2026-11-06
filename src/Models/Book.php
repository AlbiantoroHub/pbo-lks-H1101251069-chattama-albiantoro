<?php
namespace App\Models;

use App\Database\Connection;
use PDO;

class Book
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Connection::get();
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM books ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM books WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO books (judul, penulis, tahun, stok) VALUES (:judul, :penulis, :tahun, :stok)");
        return $stmt->execute([
            'judul' => $data['judul'],
            'penulis' => $data['penulis'],
            'tahun' => $data['tahun'],
            'stok' => $data['stok'] ?? 0
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("UPDATE books SET judul = :judul, penulis = :penulis, tahun = :tahun, stok = :stok WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'judul' => $data['judul'],
            'penulis' => $data['penulis'],
            'tahun' => $data['tahun'],
            'stok' => $data['stok']
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM books WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}