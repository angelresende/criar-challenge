<?php

namespace App\Repositories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Model;

class GroupRepository extends AbstractRepository implements RepositoryInterface
{
    protected function getModel(): Model
    {
        return new Group();
    }

}
