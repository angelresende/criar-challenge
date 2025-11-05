<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends AbstractRepository implements RepositoryInterface
{
    protected function getModel(): Model
    {
        return new User();
    }

}
