<?php

namespace App\Repositories;

use App\Models\Campaign;
use Illuminate\Database\Eloquent\Model;

class CampaignRepository extends AbstractRepository implements RepositoryInterface
{
    protected function getModel(): Model
    {
        return new Campaign();
    }

    public function deactivateOtherCampaigns(string $groupId, ?string $ignoreId = null): void
    {
        $query = $this->getModel()
            ->where('group_id', $groupId)
            ->where('status', 'active');

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        $query->update(['status' => 'paused']);
    }

}
