<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = ['name', 'abbreviation'];

    protected $casts = [
        'name' => 'string',
        'abbreviation' => 'string',
    ];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

}
