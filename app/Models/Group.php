<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = ['name', 'description'];

    protected $casts = [
        'name' => 'string',
        'description' => 'string',
    ];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class)->orderBy('name', 'asc');
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function activeCampaigns()
    {
        return $this->hasMany(Campaign::class)->active();
    }

    public function pastCampaigns()
    {
        return $this->hasMany(Campaign::class)->past();
    }

    public function scopeWithDefaultRelations($query)
    {
        return $query->with(['cities', 'campaigns', 'activeCampaigns', 'pastCampaigns']);
    }
}
