<?php

namespace App\Repositories;

use App\Models\Discount;
use Illuminate\Database\Eloquent\Model;

class DiscountRepository extends AbstractRepository implements RepositoryInterface
{
    protected function getModel(): Model
    {
        return new Discount();
    }

}
