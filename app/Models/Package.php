<?php

namespace App\Models;

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

    public function channels(): BelongsToMany
    {
        return $this->belongsToMany(Channel::class, 'channel_package', 'package_id', 'channel_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'package_id', 'id');
    }
}
