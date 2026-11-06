<?php
namespace App\Controllers;

use App\Services\BookService;
use App\Core\Request;
use App\Core\Response;
use App\Exceptions\ValidationException;
use InvalidArgumentException;
use Exception;

class BookController
{
    private BookService $service;

    public function __construct(BookService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): void
    {
        $search = (string) $request->getQuery('search', '');
        $page = (int) $request->getQuery('page', 1);
        $limit = (int) $request->getQuery('limit', 5);

        $result = $this->service->getPaginatedBooks($search, $page, $limit);

        Response::json([
            'success' => true,
            'message' => 'Daftar buku',
            'data' => array_map(fn($b) => $b->toArray(), $result['books']),
            'pagination' => [
                'current_page' => $result['page'],
                'limit' => $result['limit'],
                'total_items' => $result['total'],
                'total_pages' => $result['total_pages']
            ]
        ]);
    }

    public function show(Request $request, string $id): void
    {
        try {
            $book = $this->service->getBookById((int)$id);
            Response::json([
                'success' => true,
                'message' => 'Detail buku',
                'data' => $book->toArray()
            ]);
        } catch (InvalidArgumentException $e) {
            Response::json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request): void
    {
        try {
            $data = $request->getBody();
            $id = $this->service->createBook($data);
            $book = $this->service->getBookById($id);
            Response::json([
                'success' => true,
                'message' => 'Buku berhasil dibuat',
                'data' => $book->toArray()
            ], 201);
        } catch (ValidationException $e) {
            Response::json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors'  => $e->getErrors()
            ], 422);
        }
    }

    public function update(Request $request, string $id): void
    {
        try {
            $data = $request->getBody();
            $this->service->updateBook((int)$id, $data);
            $book = $this->service->getBookById((int)$id);
            Response::json([
                'success' => true,
                'message' => 'Buku berhasil diperbarui',
                'data' => $book->toArray()
            ]);
        } catch (ValidationException $e) {
            Response::json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors'  => $e->getErrors()
            ], 422);
        } catch (InvalidArgumentException $e) {
            Response::json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    public function destroy(Request $request, string $id): void
    {
        try {
            $this->service->deleteBook((int)$id);
            Response::json([
                'success' => true,
                'message' => 'Buku berhasil dihapus'
            ]);
        } catch (InvalidArgumentException $e) {
            Response::json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }
}