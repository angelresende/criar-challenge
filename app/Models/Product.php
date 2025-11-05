<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = ['name', 'description', 'base_price'];


    protected $casts = [
        'name' => 'string',
        'description' => 'string',
        'base_price' => 'decimal',
    ];

    public function scopeWithDefaultRelations($query)
    {
        return $query;
    }
}
