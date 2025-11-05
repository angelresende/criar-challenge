<?php

namespace App\Repositories;

use App\Models\State;
use Illuminate\Database\Eloquent\Model;

class StateRepository extends AbstractRepository implements RepositoryInterface
{
    protected function getModel(): Model
    {
        return new State();
    }

}
