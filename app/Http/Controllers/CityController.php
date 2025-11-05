<?php

namespace App\Http\Controllers;

use App\Services\CityService;
use App\Http\Controllers\Controller;
use App\Http\Requests\CityRequest;
use Illuminate\Http\Response;

class CityController extends Controller
{
    public function __construct(
        private CityService $cityService
    ) {
        parent::__construct();
    }

    public function index()
    {
        $cities = $this->cityService->getAll();
        return $this->apiResponse->responseEnveloper(
            data: $cities,
            status: true,
            statusCode: Response::HTTP_OK
        );
    }

    public function store(CityRequest $request)
    {
        $city = $this->cityService->create($request->validated());
        return $this->apiResponse->responseEnveloper(
            data: $city,
            status: true,
            statusCode: Response::HTTP_CREATED
        );
    }

    public function show(string $id)
    {
        $city = $this->cityService->getOne($id);
        return $this->apiResponse->responseEnveloper(
            data: $city,
            status: true,
            statusCode: Response::HTTP_OK
        );
    }

    public function update(string $id, CityRequest $request)
    {
        $city = $this->cityService->update($id, $request->validated());
        return $this->apiResponse->responseEnveloper(
            data: $city,
            status: true,
            statusCode: Response::HTTP_OK
        );
    }

    public function destroy(string $id)
    {
        $this->cityService->delete($id);

        return $this->apiResponse->responseEnveloper(
            data: ['message' => 'City deleted successfully'],
            status: true,
            statusCode: Response::HTTP_OK
        );
    }
}

