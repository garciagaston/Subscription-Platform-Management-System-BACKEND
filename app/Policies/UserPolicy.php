<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $authenticatedUser): bool
    {
        return $authenticatedUser->isAdmin() || $authenticatedUser->hasPermissionTo('view any users');
    }

    public function view(User $user1, User $user2): bool
    {
        if ($user1->id === $user2->id) {
            return true;
        }
        return $user1->isAdmin() || $user1->hasPermissionTo('view users');
    }

    public function create(User $authenticatedUser): bool
    {
        return $authenticatedUser->isAdmin() || $authenticatedUser->hasPermissionTo('create users');
    }

    public function update(User $authenticatedUser): bool
    {
        return $authenticatedUser->isAdmin() || $authenticatedUser->hasPermissionTo('edit users');
    }

    public function delete(User $user1, User $user2): bool
    {
        if ($user1->id === $user2->id) {
            return true;
        }
        return $user1->isAdmin() || $user1->hasPermissionTo('delete users');
    }
}
