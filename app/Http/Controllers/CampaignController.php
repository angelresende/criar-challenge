<?php

namespace App\Http\Controllers;

use App\Services\CampaignService;
use App\Http\Controllers\Controller;
use App\Http\Requests\CampaignRequest;
use Illuminate\Http\Response;


class CampaignController extends Controller
{
    public function __construct(
        private CampaignService $campaignService
    ) {
        parent::__construct();
    }

    public function index()
    {
        $campaigns = $this->campaignService->getAll();
        return $this->apiResponse->responseEnveloper(
            data: $campaigns,
            status: true,
            statusCode: Response::HTTP_OK
        );
    }

    public function store(CampaignRequest $request)
    {
        $campaign = $this->campaignService->create($request->validated());
        return $this->apiResponse->responseEnveloper(
            data: $campaign,
            status: true,
            statusCode: Response::HTTP_CREATED
        );
    }

    public function show(string $id)
    {
        $campaign = $this->campaignService->getOne($id);
        return $this->apiResponse->responseEnveloper(
            data: $campaign,
            status: true,
            statusCode: Response::HTTP_OK
        );
    }

    public function update(string $id, CampaignRequest $request)
    {
        $campaign = $this->campaignService->update($id, $request->validated());
        return $this->apiResponse->responseEnveloper(
            data: $campaign,
            status: true,
            statusCode: Response::HTTP_OK
        );
    }

    public function destroy(string $id)
    {
        $this->campaignService->delete($id);

        return $this->apiResponse->responseEnveloper(
            data: ['message' => 'City deleted successfully'],
            status: true,
            statusCode: Response::HTTP_OK
        );
    }
}
