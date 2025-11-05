<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Http\Resources\DiscountResource;
use App\Repositories\DiscountRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DiscountService
{
    public function __construct(
        private DiscountRepository $repository
    ) {}

    public function getAll(): AnonymousResourceCollection
    {
        $discounts = $this->repository->getAll();
        return DiscountResource::collection($discounts);
    }

    public function getOne(string $id): DiscountResource
    {
        $discount = $this->repository->getOne($id);
        if (!$discount) {
            throw new NotFoundException(['Discount not found']);
        }
        return new DiscountResource($discount);
    }

    public function create(array $data): DiscountResource
    {
        $discount = $this->repository->create($data);
        return new DiscountResource($discount);
    }

    public function update(string $id, array $data): DiscountResource
    {
        $discount = $this->repository->getOne($id);

        if (!$discount) {
            throw new NotFoundException(['Discount not found']);
        }

        $this->repository->update($data, $id);
        $updated = $this->repository->getOne($id);
        return new DiscountResource($updated);
    }

    public function delete(string $id): bool
    {
        return $this->repository->delete($id);
    }
}
