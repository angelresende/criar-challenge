<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {
        parent::__construct();
    }

    public function login(LoginRequest $request)
    {
        $data = $this->authService->login($request->validated());
        return $this->apiResponse->responseEnveloper(
            data: $data,
            status: true,
            statusCode: Response::HTTP_OK
        );
    }

    public function me()
    {
        $data = $this->authService->me();
        return $this->apiResponse->responseEnveloper(
            data: $data,
            status: true,
            statusCode: Response::HTTP_OK
        );
    }

    public function logout()
    {
        $this->authService->logout();
        return $this->apiResponse->responseEnveloper(
            data: ['message' => 'Logged out successfully'],
            status: true,
            statusCode: Response::HTTP_OK
        );
    }
}
