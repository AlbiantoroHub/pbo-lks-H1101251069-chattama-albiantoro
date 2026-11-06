<?php
namespace App\Services;

use App\Contracts\BookRepositoryInterface;
use App\Core\Validator;
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
        Validator::validate($data, [
            'judul'   => 'required',
            'penulis' => 'required',
            'tahun'   => 'required|numeric|min:1900',
            'stok'    => 'numeric|min:0'
        ]);

        return $this->repository->create($data);
    }

    public function updateBook(int $id, array $data): bool
    {
        $this->getBookById($id);

        Validator::validate($data, [
            'judul'   => 'required',
            'penulis' => 'required',
            'tahun'   => 'required|numeric|min:1900',
            'stok'    => 'numeric|min:0'
        ]);

        return $this->repository->update($id, $data);
    }

    public function deleteBook(int $id): bool
    {
        $this->getBookById($id);
        return $this->repository->delete($id);
    }
}