<?php
namespace App\Services;

use App\Contracts\CategoryRepositoryInterface;
use InvalidArgumentException;

class CategoryService
{
    private CategoryRepositoryInterface $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAllCategories(): array
    {
        return $this->repository->getAll();
    }

    public function getCategoryById(int $id)
    {
        $category = $this->repository->find($id);
        if (!$category) {
            throw new InvalidArgumentException("Kategori dengan ID $id tidak ditemukan");
        }
        return $category;
    }

    public function createCategory(array $data): int
    {
        if (empty($data['nama'])) {
            throw new InvalidArgumentException("Nama kategori wajib diisi");
        }
        return $this->repository->create($data);
    }

    public function updateCategory(int $id, array $data): bool
    {
        $this->getCategoryById($id);
        if (empty($data['nama'])) {
            throw new InvalidArgumentException("Nama kategori wajib diisi");
        }
        return $this->repository->update($id, $data);
    }

    public function deleteCategory(int $id): bool
    {
        $this->getCategoryById($id);
        return $this->repository->delete($id);
    }
}