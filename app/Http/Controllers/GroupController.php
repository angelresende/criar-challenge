<?php

namespace App\Http\Controllers;

use App\Services\GroupService;
use App\Http\Controllers\Controller;
use App\Http\Requests\GroupRequest;

use Illuminate\Http\Response;

class GroupController extends Controller
{
    public function __construct(
        private GroupService $groupService
    ) {
        parent::__construct();
    }

    public function index()
    {
        $groups = $this->groupService->getAll();
        return $this->apiResponse->responseEnveloper(
            data: $groups,
            status: true,
            statusCode: Response::HTTP_OK
        );
    }

    public function store(GroupRequest $request)
    {
        $group = $this->groupService->create($request->validated());
        return $this->apiResponse->responseEnveloper(
            data: $group,
            status: true,
            statusCode: Response::HTTP_CREATED
        );
    }

    public function show(string $id)
    {
        $group = $this->groupService->getOne($id);
        return $this->apiResponse->responseEnveloper(
            data: $group,
            status: true,
            statusCode: Response::HTTP_OK
        );
    }

    public function update(string $id, GroupRequest $request)
    {
        $group = $this->groupService->update($id, $request->validated());
        return $this->apiResponse->responseEnveloper(
            data: $group,
            status: true,
            statusCode: Response::HTTP_OK
        );
    }

    public function destroy(string $id)
    {
        $this->groupService->delete($id);

        return $this->apiResponse->responseEnveloper(
            data: ['message' => 'Group deleted successfully'],
            status: true,
            statusCode: Response::HTTP_OK
        );
    }
}
