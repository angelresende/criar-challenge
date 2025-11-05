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

}
