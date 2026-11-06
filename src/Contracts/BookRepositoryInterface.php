<?php
namespace App\Contracts;

use App\Models\Book;

interface BookRepositoryInterface
{
    public function getAll(): array;
    public function find(int $id): ?Book;
    public function create(array $data): int;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function paginate(string $search = '', int $page = 1, int $limit = 10, string $sortBy = 'id', string $order = 'DESC'): array;
}