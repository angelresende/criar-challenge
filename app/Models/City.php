<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = ['group_id', 'state_id', 'name'];

    protected $casts = [
        'group_id' => 'string',
        'state_id' => 'string',
        'name' => 'string',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

     public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
}
