<?php

namespace App\Http\Controllers;

use App\Services\DiscountService;
use App\Http\Controllers\Controller;
use App\Http\Requests\DiscountRequest;
use Illuminate\Http\Response;

class DiscountController extends Controller
{
    public function __construct(
        private DiscountService $discountService
    ) {
        parent::__construct();
    }

    public function index()
    {
        $discounts = $this->discountService->getAll();
        return $this->apiResponse->responseEnveloper(
            data: $discounts,
            status: true,
            statusCode: Response::HTTP_OK
        );
    }

    public function store(DiscountRequest $request)
    {
        $campaign = $this->discountService->create($request->validated());
        return $this->apiResponse->responseEnveloper(
            data: $campaign,
            status: true,
            statusCode: Response::HTTP_CREATED
        );
    }

    public function show(string $id)
    {
        $campaign = $this->discountService->getOne($id);
        return $this->apiResponse->responseEnveloper(
            data: $campaign,
            status: true,
            statusCode: Response::HTTP_OK
        );
    }

    public function update(string $id, DiscountRequest $request)
    {
        $campaign = $this->discountService->update($id, $request->validated());
        return $this->apiResponse->responseEnveloper(
            data: $campaign,
            status: true,
            statusCode: Response::HTTP_OK
        );
    }

    public function destroy(string $id)
    {
        $this->discountService->delete($id);

        return $this->apiResponse->responseEnveloper(
            data: ['message' => 'Discount deleted successfully'],
            status: true,
            statusCode: Response::HTTP_OK
        );
    }
}

