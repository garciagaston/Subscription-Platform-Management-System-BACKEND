<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'sku',
        'image_url',
        'active'
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
        return $this->belongsToMany(Channel::class, 'channel_package', 'package_id', 'channel_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'package_id', 'id');
    }
}
