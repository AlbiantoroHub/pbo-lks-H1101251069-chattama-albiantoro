<?php
namespace App\Repositories;

use App\Contracts\CategoryRepositoryInterface;
use App\Models\Category;
use App\Database\Connection;
use PDO;

class MySQLCategoryRepository implements CategoryRepositoryInterface
{
    private PDO $pdo;

    public function __construct()
    {
        // Menyesuaikan method koneksi yang ada di proyekmu
        if (method_exists(Connection::class, 'getConnection')) {
            $this->pdo = Connection::getConnection();
        } elseif (method_exists(Connection::class, 'getInstance')) {
            $this->pdo = Connection::getInstance();
        } else {
            $this->pdo = Connection::get();
        }
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY id ASC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new Category($row['nama'], $row['deskripsi'], (int)$row['id']), $rows);
    }

    public function find(int $id): ?Category
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Category($row['nama'], $row['deskripsi'], (int)$row['id']) : null;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO categories (nama, deskripsi) VALUES (:nama, :deskripsi)");
        $stmt->execute(['nama' => $data['nama'], 'deskripsi' => $data['deskripsi'] ?? '']);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("UPDATE categories SET nama = :nama, deskripsi = :deskripsi WHERE id = :id");
        return $stmt->execute(['nama' => $data['nama'], 'deskripsi' => $data['deskripsi'] ?? '', 'id' => $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}