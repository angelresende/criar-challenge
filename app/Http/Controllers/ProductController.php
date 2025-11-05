<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {
        parent::__construct();
    }

    public function index()
    {
        $products = $this->productService->getAll();
        return $this->apiResponse->responseEnveloper(
            data: $products,
            status: true,
            statusCode: Response::HTTP_OK
        );
    }

    public function store(ProductRequest $request)
    {
        $product = $this->productService->create($request->validated());
        return $this->apiResponse->responseEnveloper(
            data: $product,
            status: true,
            statusCode: Response::HTTP_CREATED
        );
    }

    public function show(string $id)
    {
        $product = $this->productService->getOne($id);
        return $this->apiResponse->responseEnveloper(
            data: $product,
            status: true,
            statusCode: Response::HTTP_OK
        );
    }

    public function update(string $id, ProductRequest $request)
    {
        $product = $this->productService->update($id, $request->validated());
        return $this->apiResponse->responseEnveloper(
            data: $product,
            status: true,
            statusCode: Response::HTTP_OK
        );
    }

    public function destroy(string $id)
    {
        $this->productService->delete($id);

        return $this->apiResponse->responseEnveloper(
            data: ['message' => 'Product deleted successfully'],
            status: true,
            statusCode: Response::HTTP_OK
        );
    }
}
