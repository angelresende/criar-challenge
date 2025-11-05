<?php

namespace App\Repositories;

use App\Models\City;
use Illuminate\Database\Eloquent\Model;

class CityRepository extends AbstractRepository implements RepositoryInterface
{
    protected function getModel(): Model
    {
        return new City();
    }

}
