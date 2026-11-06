<?php
namespace App\Controllers;

use App\Services\CategoryService;
use App\Core\Request;
use App\Core\Response;
use InvalidArgumentException;

class CategoryController
{
    private CategoryService $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): void
    {
        $categories = $this->service->getAllCategories();
        Response::json([
            'success' => true,
            'message' => 'Daftar kategori',
            'data' => array_map(fn($cat) => $cat->toArray(), $categories)
        ]);
    }

    public function show(Request $request, string $id): void
    {
        try {
            $category = $this->service->getCategoryById((int)$id);
            Response::json([
                'success' => true,
                'message' => 'Detail kategori',
                'data' => $category->toArray()
            ]);
        } catch (InvalidArgumentException $e) {
            Response::json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request): void
    {
        try {
            $data = $request->getBody();
            $id = $this->service->createCategory($data);
            $category = $this->service->getCategoryById($id);
            Response::json([
                'success' => true,
                'message' => 'Kategori berhasil dibuat',
                'data' => $category->toArray()
            ], 201);
        } catch (InvalidArgumentException $e) {
            Response::json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function update(Request $request, string $id): void
    {
        try {
            $data = $request->getBody();
            $this->service->updateCategory((int)$id, $data);
            $category = $this->service->getCategoryById((int)$id);
            Response::json([
                'success' => true,
                'message' => 'Kategori berhasil diperbarui',
                'data' => $category->toArray()
            ]);
        } catch (InvalidArgumentException $e) {
            $status = str_contains($e->getMessage(), 'tidak ditemukan') ? 404 : 422;
            Response::json(['success' => false, 'message' => $e->getMessage()], $status);
        }
    }

    public function destroy(Request $request, string $id): void
    {
        try {
            $this->service->deleteCategory((int)$id);
            Response::json([
                'success' => true,
                'message' => 'Kategori berhasil dihapus'
            ]);
        } catch (InvalidArgumentException $e) {
            Response::json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }
}