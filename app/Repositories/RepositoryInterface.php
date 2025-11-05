<?php

namespace App\Repositories;

use App\Exceptions\ApiException;
use App\Exceptions\NotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

interface RepositoryInterface
{
    public function getAll(): Model|Collection;
    public function getOne(string $id): Model|null;
    public function create(array $data): Model|ApiException;
    public function update(array $data, string $id): bool|ApiException|NotFoundException;
    public function delete(string $id): bool|ApiException|NotFoundException;
}
