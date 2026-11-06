<?php
namespace App\Services;

use App\Contracts\BookRepositoryInterface;
use InvalidArgumentException;

class BookService
{
    private BookRepositoryInterface $repository;

    public function __construct(BookRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAllBooks(): array
    {
        return $this->repository->getAll();
    }

    public function getBookById(int $id)
    {
        $book = $this->repository->find($id);
        if (!$book) {
            throw new InvalidArgumentException("Buku dengan ID $id tidak ditemukan");
        }
        return $book;
    }

    public function createBook(array $data): int
    {
        $this->validateBookData($data);
        return $this->repository->create($data);
    }

    public function updateBook(int $id, array $data): bool
    {
        $this->getBookById($id); // Pastikan buku ada
        $this->validateBookData($data);
        return $this->repository->update($id, $data);
    }

    public function deleteBook(int $id): bool
    {
        $this->getBookById($id); // Pastikan buku ada
        return $this->repository->delete($id);
    }

    private function validateBookData(array $data): void
    {
        if (empty($data['judul'])) {
            throw new InvalidArgumentException("Judul wajib diisi");
        }
        if (empty($data['penulis'])) {
            throw new InvalidArgumentException("Penulis wajib diisi");
        }
        if (empty($data['tahun']) || !is_numeric($data['tahun'])) {
            throw new InvalidArgumentException("Tahun wajib diisi dengan angka");
        }
    }
}