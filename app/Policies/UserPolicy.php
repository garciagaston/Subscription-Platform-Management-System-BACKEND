<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $authenticatedUser): bool
    {
        return $authenticatedUser->isAdmin() || $authenticatedUser->hasPermissionTo('view any users');
    }

    public function view(User $authenticatedUser, User $user): bool
    {
        return $authenticatedUser->isAdmin() || $authenticatedUser->hasPermissionTo('view users') || $authenticatedUser->id === $user->id;
    }

    public function create(User $authenticatedUser): bool
    {
        return $authenticatedUser->isAdmin() || $authenticatedUser->hasPermissionTo('create users');
    }

    public function update(User $authenticatedUser, User $user): bool
    {
        return $authenticatedUser->isAdmin() || $authenticatedUser->hasPermissionTo('edit users') || $authenticatedUser->id === $user->id;
    }

    public function delete(User $authenticatedUser, User $user): bool
    {
        return $authenticatedUser->isAdmin() || $authenticatedUser->hasPermissionTo('delete users') || $authenticatedUser->id === $user->id;
    }
}
