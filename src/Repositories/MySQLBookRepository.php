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

    public function paginate(string $search = '', int $page = 1, int $limit = 10, string $sortBy = 'id', string $order = 'DESC'): array
    {
        $offset = ($page - 1) * $limit;
        $params = [];
        $where = '';

        if (!empty($search)) {
            $where = "WHERE judul LIKE :search1 OR penulis LIKE :search2";
            $params['search1'] = "%$search%";
            $params['search2'] = "%$search%";
        }

        // Whitelist kolom dan arah sorting untuk keamanan
        $allowedColumns = ['id', 'judul', 'penulis', 'tahun', 'stok'];
        $column = in_array(strtolower($sortBy), $allowedColumns, true) ? strtolower($sortBy) : 'id';
        $direction = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';

        // Hitung total data
        $countSql = "SELECT COUNT(*) FROM books $where";
        $stmtCount = $this->pdo->prepare($countSql);
        $stmtCount->execute($params);
        $total = (int) $stmtCount->fetchColumn();

        // Ambil data dengan LIMIT, OFFSET, dan dynamic ORDER BY
        $dataSql = "SELECT * FROM books $where ORDER BY $column $direction LIMIT $limit OFFSET $offset";
        $stmtData = $this->pdo->prepare($dataSql);
        foreach ($params as $k => $v) {
            $stmtData->bindValue(":$k", $v);
        }
        $stmtData->execute();
        $rows = $stmtData->fetchAll();

        $books = array_map(function ($row) {
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

        return [
            'books' => $books,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'total_pages' => (int) ceil($total / $limit)
        ];
    }
}