<?php
namespace App\Controllers;

use App\Models\Book;

class BookController
{
    private Book $model;

    public function __construct()
    {
        $this->model = new Book();
    }

    private function jsonResponse(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function index(): void
    {
        $books = $this->model->getAll();
        $this->jsonResponse([
            'status' => 'success',
            'data'   => $books
        ]);
    }

    public function show(int $id): void
    {
        $book = $this->model->find($id);
        if (!$book) {
            $this->jsonResponse([
                'status'  => 'error',
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        $this->jsonResponse([
            'status' => 'success',
            'data'   => $book
        ]);
    }

    public function store(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['judul']) || !isset($input['penulis']) || !isset($input['tahun'])) {
            $this->jsonResponse([
                'status'  => 'error',
                'message' => 'Kolom judul, penulis, dan tahun wajib diisi'
            ], 422);
        }

        $created = $this->model->create($input);

        if ($created) {
            $this->jsonResponse([
                'status'  => 'success',
                'message' => 'Buku berhasil ditambahkan'
            ], 201);
        }

        $this->jsonResponse([
            'status'  => 'error',
            'message' => 'Gagal menambahkan buku'
        ], 500);
    }

    public function update(int $id): void
    {
        $book = $this->model->find($id);
        if (!$book) {
            $this->jsonResponse([
                'status'  => 'error',
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $updated = $this->model->update($id, [
            'judul'   => $input['judul'] ?? $book['judul'],
            'penulis' => $input['penulis'] ?? $book['penulis'],
            'tahun'   => $input['tahun'] ?? $book['tahun'],
            'stok'    => $input['stok'] ?? $book['stok'],
        ]);

        if ($updated) {
            $this->jsonResponse([
                'status'  => 'success',
                'message' => 'Buku berhasil diperbarui'
            ]);
        }

        $this->jsonResponse([
            'status'  => 'error',
            'message' => 'Gagal memperbarui buku'
        ], 500);
    }

    public function destroy(int $id): void
    {
        $book = $this->model->find($id);
        if (!$book) {
            $this->jsonResponse([
                'status'  => 'error',
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        $deleted = $this->model->delete($id);
        if ($deleted) {
            $this->jsonResponse([
                'status'  => 'success',
                'message' => 'Buku berhasil dihapus'
            ]);
        }

        $this->jsonResponse([
            'status'  => 'error',
            'message' => 'Gagal menghapus buku'
        ], 500);
    }
}