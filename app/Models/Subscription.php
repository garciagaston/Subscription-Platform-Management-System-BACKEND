<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Subscription extends Model implements ContractsAuditable
{
    use Auditable, HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'package_id',
        'start_date',
        'end_date',
    ];

    protected $casts = [
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
            $filter = $query->where('start_date', '<=', Carbon::now())->where('end_date', '>=', Carbon::now());
        }

        return $filter;
    }

    public function getActiveAttribute(): bool
    {
        return ($this->start_date <= Carbon::now()) && ($this->end_date >= Carbon::now());
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
