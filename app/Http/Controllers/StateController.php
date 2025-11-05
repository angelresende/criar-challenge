<?php

namespace App\Http\Controllers;

use App\Services\StateService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StateRequest;
use Illuminate\Http\Response;

class StateController extends Controller
{
    public function __construct(
        private StateService $stateService
    ) {
        parent::__construct();
    }

    public function index()
    {
        $states = $this->stateService->getAll();
        return $this->apiResponse->responseEnveloper(
            data: $states,
            status: true,
            statusCode: Response::HTTP_OK
        );
    }

    public function store(StateRequest $request)
    {
        $state = $this->stateService->create($request->validated());
        return $this->apiResponse->responseEnveloper(
            data: $state,
            status: true,
            statusCode: Response::HTTP_CREATED
        );
    }

    public function show(string $id)
    {
        $state = $this->stateService->getOne($id);
        return $this->apiResponse->responseEnveloper(
            data: $state,
            status: true,
            statusCode: Response::HTTP_OK
        );
    }

    public function update(string $id, StateRequest $request)
    {
        $state = $this->stateService->update($id, $request->validated());
        return $this->apiResponse->responseEnveloper(
            data: $state,
            status: true,
            statusCode: Response::HTTP_OK
        );
    }

    public function destroy(string $id)
    {
        $this->stateService->delete($id);

        return $this->apiResponse->responseEnveloper(
            data: ['message' => 'State deleted successfully'],
            status: true,
            statusCode: Response::HTTP_OK
        );
    }
}
