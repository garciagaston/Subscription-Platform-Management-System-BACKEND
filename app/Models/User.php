<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements ContractsAuditable
{
    use Auditable;
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function scopeFilter(Builder $query, $request)
    {
        $filter = $query;
        if (isset($request->name)) {
            $filter = $query->where('name', 'like', "%{$request->name}%");
        }
        if (isset($request->email)) {
            $filter = $query->where('email', 'like', "%{$request->email}%");
        }

        return $filter;
    }

    public function isAdmin()
    {
        return $this->roles()->where('name', 'admin')->exists();
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'user_id', 'id');
    }
}
