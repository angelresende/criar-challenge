<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Http\Resources\GroupResource;
use App\Repositories\GroupRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GroupService
{
    public function __construct(
        private GroupRepository $repository
    ) {}

    public function getAll(): AnonymousResourceCollection
    {
        $groups = $this->repository->getAll();
        return GroupResource::collection($groups);
    }

    public function getOne(string $id): GroupResource
    {
        $group = $this->repository->getOne($id);
        if (!$group) {
            throw new NotFoundException(['Group not found']);
        }
        return new GroupResource($group);
    }

    public function create(array $data): GroupResource
    {
        $group = $this->repository->create($data);
        return new GroupResource($group);
    }

    public function update(string $id, array $data): GroupResource
    {
        $group = $this->repository->getOne($id);

        if (!$group) {
            throw new NotFoundException(['Group not found']);
        }

        $this->repository->update($data, $id);
        $updated = $this->repository->getOne($id);
        return new GroupResource($updated);
    }

    public function delete(string $id): bool
    {
        return $this->repository->delete($id);
    }
}
