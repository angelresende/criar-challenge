<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Http\Resources\CityResource;
use App\Repositories\CityRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CityService
{
    public function __construct(
        private CityRepository $repository
    ) {}

    public function getAll(): AnonymousResourceCollection
    {
        $cities = $this->repository->getAll();
        return CityResource::collection($cities);
    }

    public function getOne(string $id): CityResource
    {
        $city = $this->repository->getOne($id);
        if (!$city) {
            throw new NotFoundException(['City not found']);
        }
        return new CityResource($city);
    }

    public function create(array $data): CityResource
    {
        $city = $this->repository->create($data);
        return new CityResource($city);
    }

    public function update(string $id, array $data): CityResource
    {
        $city = $this->repository->getOne($id);

        if (!$city) {
            throw new NotFoundException(['City not found']);
        }

        $this->repository->update($data, $id);
        $updated = $this->repository->getOne($id);
        return new CityResource($updated);
    }

    public function delete(string $id): bool
    {
        return $this->repository->delete($id);
    }
}
