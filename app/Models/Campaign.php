<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = ['group_id', 'name', 'description', 'status', 'start_date', 'end_date'];

    protected $casts = [
        'group_id' => 'string',
        'name' => 'string',
        'description' => 'string',
        'status' => 'string',
        'start_date' => 'date:d/m/Y',
        'end_date' => 'date:d/m/Y',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class)->orderBy('created_at', 'asc');
    }


    public function scopeWithDefaultRelations($query)
    {
        return $query->with('group');
    }

}
