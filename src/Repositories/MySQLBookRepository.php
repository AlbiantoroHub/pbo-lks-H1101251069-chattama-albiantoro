<?php
namespace App\Repositories;

use App\Contracts\BookRepositoryInterface;
use App\Database\Connection;
use App\Models\Book;
use PDO;

class MySQLBookRepository implements BookRepositoryInterface
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Connection::get();
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM books ORDER BY id DESC");
        $rows = $stmt->fetchAll();

        return array_map(function ($row) {
            return new Book(
                $row['judul'],
                $row['penulis'],
                (int)$row['tahun'],
                (int)$row['stok'],
                (int)$row['id'],
                $row['created_at'],
                $row['updated_at']
            );
        }, $rows);
    }

    public function find(int $id): ?Book
    {
        $stmt = $this->pdo->prepare("SELECT * FROM books WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) return null;

        return new Book(
            $row['judul'],
            $row['penulis'],
            (int)$row['tahun'],
            (int)$row['stok'],
            (int)$row['id'],
            $row['created_at'],
            $row['updated_at']
        );
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO books (judul, penulis, tahun, stok) VALUES (:judul, :penulis, :tahun, :stok)");
        $stmt->execute([
            'judul' => $data['judul'],
            'penulis' => $data['penulis'],
            'tahun' => $data['tahun'],
            'stok' => $data['stok'] ?? 0
        ]);
        return (int) $this->pdo->lastInsertId();
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