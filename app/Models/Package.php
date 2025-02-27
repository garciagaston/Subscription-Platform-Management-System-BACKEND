<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Package extends Model implements ContractsAuditable
{
    use Auditable, HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'sku',
        'image_url',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function scopeFilter(Builder $query, $request)
    {
        $filter = $query;
        if (isset($request->name)) {
            $filter = $query->where('name', 'like', "%{$request->name}%");
        }
        if (isset($request->sku)) {
            $filter = $query->where('sku', $request->sku);
        }
        if (isset($request->active)) {
            $filter = $query->where('active', $request->active);
        }

        return $filter;
    }

    public function channels(): BelongsToMany
    {
        return $this->belongsToMany(Channel::class, 'package_channel', 'package_id', 'channel_id')->withTimestamps();
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'package_id', 'id');
    }
}
