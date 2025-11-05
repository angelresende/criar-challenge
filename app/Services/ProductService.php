<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductService
{
    public function __construct(
        private ProductRepository $repository
    ) {}

    public function getAll(): AnonymousResourceCollection
    {
        $products = $this->repository->getAll();
        return ProductResource::collection($products);
    }

    public function getOne(string $id): ProductResource
    {
        $product = $this->repository->getOne($id);
        if (!$product) {
            throw new NotFoundException(['Product not found']);
        }
        return new ProductResource($product);
    }

    public function create(array $data): ProductResource
    {
        $product = $this->repository->create($data);
        return new ProductResource($product);
    }

    public function update(string $id, array $data): ProductResource
    {
        $product = $this->repository->getOne($id);

        if (!$product) {
            throw new NotFoundException(['Product not found']);
        }

        $this->repository->update($data, $id);
        $updated = $this->repository->getOne($id);
        return new ProductResource($updated);
    }

    public function delete(string $id): bool
    {
        return $this->repository->delete($id);
    }
}
