<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Http\Resources\CampaignResource;
use App\Repositories\CampaignRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CampaignService
{
    public function __construct(
        private CampaignRepository $repository
    ) {}

    public function getAll(): AnonymousResourceCollection
    {
        $campaigns = $this->repository->getAll();
        return CampaignResource::collection($campaigns);
    }

    public function getOne(string $id): CampaignResource
    {
        $campaign = $this->repository->getOne($id);
        if (!$campaign) {
            throw new NotFoundException(['Campaign not found']);
        }
        return new CampaignResource($campaign);
    }

    public function create(array $data): CampaignResource
    {
        if (isset($data['status']) && $data['status'] === 'active') {
            $this->deactivateOtherCampaigns($data['group_id']);
        }

        $campaign = $this->repository->create($data);

        return new CampaignResource($campaign->load('discounts'));
    }

    public function update(string $id, array $data): CampaignResource
    {
        $campaign = $this->repository->getOne($id);

        if (!$campaign) {
            throw new NotFoundException(['Campaign not found']);
        }

        // Regra: se for atualizar para active, desativa as outras
        if (isset($data['status']) && $data['status'] === 'active') {
            $this->deactivateOtherCampaigns($campaign->group_id, $campaign->id);
        }

        $this->repository->update($data, $id);
        $updated = $this->repository->getOne($id);

        return new CampaignResource($updated->load('discounts'));
    }

    public function delete(string $id): bool
    {
        return $this->repository->delete($id);
    }

    private function deactivateOtherCampaigns(string $groupId, ?string $ignoreId = null): void
    {
        $this->repository->deactivateOtherCampaigns($groupId, $ignoreId);
    }

}
