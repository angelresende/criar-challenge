<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserService
{
    public function __construct(
        private UserRepository $repository
    ) {}

    public function getAll(): AnonymousResourceCollection
    {
        $users = $this->repository->getAll();
        return UserResource::collection($users);
    }

    public function getOne(string $id): UserResource
    {
        $user = $this->repository->getOne($id);
        if (!$user) {
            throw new NotFoundException(['User not found']);
        }
        return new UserResource($user);
    }

    public function create(array $data): UserResource
    {
        $user = $this->repository->create($data);
        return new UserResource($user);
    }

    public function update(string $id, array $data): UserResource
    {
        $user = $this->repository->getOne($id);

        if (!$user) {
            throw new NotFoundException(['User not found']);
        }

        $this->repository->update($data, $id);
        $updated = $this->repository->getOne($id);
        return new UserResource($updated);
    }

    public function delete(string $id): bool
    {
        return $this->repository->delete($id);
    }
}
