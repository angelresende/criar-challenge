<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class ProductRepository extends AbstractRepository implements RepositoryInterface
{
    protected function getModel(): Model
    {
        return new Product();
    }

}
