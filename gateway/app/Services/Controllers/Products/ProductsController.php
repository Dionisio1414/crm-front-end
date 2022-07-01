<?php

namespace App\Services\Controllers\Products;

use App\Core\Http\Controllers\Controller;
use App\Services\Service\ProductService;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    use ApiResponder;

    public $productService;

    /**
     * Create a new controller instance.
     *
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(): JsonResponse
    {
        return $this->successResponse($this->productService->getProducts());
    }

//    public function store(Request $request): JsonResponse
//    {
//        $this->authorService->getAuthor($request->author_id);
//
//        return $this->successResponse($this->bookService->createBook($request->all()));
//    }
//
//    public function show($book): JsonResponse
//    {
//        return $this->successResponse($this->bookService->getBook($book));
//    }
//
//    public function update(Request $request, $book): JsonResponse
//    {
//        return $this->successResponse($this->bookService->editBook(($request->all()),
//            $book));
//    }
//
//    public function destroy($book): JsonResponse
//    {
//        return $this->successResponse($this->bookService->deleteBook($book));
//    }
}
