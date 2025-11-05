<?php

namespace App\Repositories;

use Exception;
use App\Helpers\Helper;
use Illuminate\Http\Response;

use App\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\NotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

abstract class AbstractRepository implements RepositoryInterface
{
    protected Model $model;

    public function __construct()
    {
        $this->model = $this->getModel();
    }

    abstract protected function getModel(): Model;

    public function getAll(): Model|Collection
    {
        return $this->model->withDefaultRelations()->get();
    }

    public function getOne(string $id): ?Model
    {
        return $this->model->withDefaultRelations()->find($id);
    }

    public function create(array $data): Model|ApiException
    {
        DB::beginTransaction();
        try {
            $model = $this->model->create($data);

            DB::commit();
            return $model;
        } catch (Exception $e) {
            Log::channel('criarApi')->warning(
               'Exception on create',
                [
                    'messageError' => $e->getMessage(),
                    'codeError' => $e->getCode(),
                    'lineError' => $e->getLine(),
                    'fileError' => $e->getFile()
                ]
            );
            DB::rollBack();
            throw new ApiException($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(array $data, string $id): bool|ApiException|NotFoundException
    {
        DB::beginTransaction();
        try {
            $model = $this->model->find($id);

            if (!$model) {
                throw new NotFoundException(['Record not found']);
            }

            $model->update($data);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new ApiException($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(string $id): bool|ApiException|NotFoundException
    {
        DB::beginTransaction();
        try {
            $model = $this->model->find($id);

            if (!$model) {
                throw new NotFoundException(['Record not found']);
            }

            $model->delete();
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new ApiException($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
