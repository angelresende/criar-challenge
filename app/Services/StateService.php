<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Http\Resources\StateResource;
use App\Repositories\StateRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StateService
{
    public function __construct(
        private StateRepository $repository
    ) {}

    public function getAll(): AnonymousResourceCollection
    {
        $states = $this->repository->getAll();
        return StateResource::collection($states);
    }

    public function getOne(string $id): StateResource
    {
        $state = $this->repository->getOne($id);
        if (!$state) {
            throw new NotFoundException(['State not found']);
        }
        return new StateResource($state);
    }

    public function create(array $data): StateResource
    {
        $state = $this->repository->create($data);
        return new StateResource($state);
    }

    public function update(string $id, array $data): StateResource
    {
        $state = $this->repository->getOne($id);

        if (!$state) {
            throw new NotFoundException(['State not found']);
        }

        $this->repository->update($data, $id);
        $updated = $this->repository->getOne($id);
        return new StateResource($updated);
    }

    public function delete(string $id): bool
    {
        return $this->repository->delete($id);
    }
}
