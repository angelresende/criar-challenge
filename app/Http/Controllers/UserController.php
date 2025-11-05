<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {
        parent::__construct();
    }

    public function index()
    {
        $users = $this->userService->getAll();
        return $this->apiResponse->responseEnveloper(
            data: $users,
            status: true,
            statusCode: Response::HTTP_OK
        );
    }

    public function store(UserRequest $request)
    {
        $user = $this->userService->create($request->validated());
        return $this->apiResponse->responseEnveloper(
            data: $user,
            status: true,
            statusCode: Response::HTTP_CREATED
        );
    }

    public function show(string $id)
    {
        $user = $this->userService->getOne($id);
        return $this->apiResponse->responseEnveloper(
            data: $user,
            status: true,
            statusCode: Response::HTTP_OK
        );
    }

    public function update(string $id, UserRequest $request)
    {
        $user = $this->userService->update($id, $request->validated());
        return $this->apiResponse->responseEnveloper(
            data: $user,
            status: true,
            statusCode: Response::HTTP_OK
        );
    }

    public function destroy(string $id)
    {
        $this->userService->delete($id);

        return $this->apiResponse->responseEnveloper(
            data: ['message' => 'User deleted successfully'],
            status: true,
            statusCode: Response::HTTP_OK
        );
    }
}

