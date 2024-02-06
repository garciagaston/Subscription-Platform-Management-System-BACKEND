<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Channel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'image_url',
        'lineup_id',
        'call_sign',
        'active',
        'display_order'
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
        if (isset($request->call_sign)) {
            $filter = $query->where('call_sign', $request->call_sign);
        }
        if (isset($request->active)) {
            $filter = $query->where('active', $request->active);
        }
        return $filter;
    }

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'package_channel', 'channel_id', 'package_id')->withTimestamps();
    }

}
