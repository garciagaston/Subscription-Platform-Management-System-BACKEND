<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Subscription extends Model implements ContractsAuditable
{
    use HasFactory;
    use SoftDeletes;
    use Auditable;

    protected $fillable = [
        'user_id',
        'package_id',
        'start_date',
        'end_date',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function scopeFilter(Builder $query, $request)
    {
        $filter = $query;
        if (isset($request->user_id)) {
            $filter = $query->where('user_id', $request->user_id);
        }
        if (isset($request->package_id)) {
            $filter = $query->where('package_id', $request->package_id);
        }
        if (isset($request->active)) {
            $filter = $query->where('active', $request->active);
        }
        return $filter;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }
}
