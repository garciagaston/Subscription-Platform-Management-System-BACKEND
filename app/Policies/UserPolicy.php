<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function index(User $authenticatedUser): bool
    {
        return $authenticatedUser->isAdmin() && $authenticatedUser->hasDirectPermission('view users');
    }

    public function show(User $user1, User $user2): bool
    {
        if ($user1->id === $user2->id) {
            return true;
        }
        return $user1->isAdmin() && $user1->hasDirectPermission('view users');
    }

    public function store(User $authenticatedUser): bool
    {
        return $authenticatedUser->isAdmin() && $authenticatedUser->hasDirectPermission('create users');
    }

    public function update(User $authenticatedUser): bool
    {
        return $authenticatedUser->isAdmin() && $authenticatedUser->hasDirectPermission('edit users');
    }

    public function destroy(User $user1, User $user2): bool
    {
        if ($user1->id === $user2->id) {
            return true;
        }
        return $user1->isAdmin() && $user1->hasDirectPermission('delete users');
    }
}
