<?php

namespace App\Policies;

use App\Models\User;

class ChannelPolicy
{
    public function viewAny(User $authenticatedUser): bool
    {
        return $authenticatedUser->isAdmin() || $authenticatedUser->hasPermissionTo('view any channels');
    }

    public function view(User $user): bool
    {
        return $user->isAdmin() || $user->hasPermissionTo('view channels');
    }

    public function create(User $authenticatedUser): bool
    {
        return $authenticatedUser->isAdmin() || $authenticatedUser->hasPermissionTo('create channels');
    }

    public function update(User $authenticatedUser): bool
    {
        return $authenticatedUser->isAdmin() || $authenticatedUser->hasPermissionTo('edit channels');
    }

    public function delete(User $user): bool
    {
        return $user->isAdmin() || $user->hasPermissionTo('delete channels');
    }
}
