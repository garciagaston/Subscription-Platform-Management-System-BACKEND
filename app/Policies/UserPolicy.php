<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function index(User $authenticatedUser): bool
    {
        return $authenticatedUser->isAdmin();
    }

    public function show(User $user1, User $user2): bool
    {
        if ($user1->id === $user2->id) {
            return true;
        }
        return $user1->isAdmin();
    }

    public function store(User $authenticatedUser): bool
    {
        return $authenticatedUser->isAdmin();
    }

    public function update(User $authenticatedUser): bool
    {
        return $authenticatedUser->isAdmin();
    }

    public function delete(User $authenticatedUser): bool
    {
        return $authenticatedUser->isAdmin();
    }
}
