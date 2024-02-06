<?php

namespace App\Policies;

use App\Models\Subscription;
use App\Models\User;

class SubscriptionPolicy
{
    public function viewAny(User $authenticatedUser): bool
    {
        return $authenticatedUser->isAdmin() || $authenticatedUser->hasPermissionTo('view any subscriptions');
    }

    public function view(User $user, Subscription $subscription): bool
    {
        return $user->isAdmin() || $user->hasPermissionTo('view subscriptions');
    }

    public function create(User $authenticatedUser): bool
    {
        return $authenticatedUser->isAdmin() || $authenticatedUser->hasPermissionTo('create subscriptions');
    }

    public function update(User $authenticatedUser): bool
    {
        return $authenticatedUser->isAdmin() || $authenticatedUser->hasPermissionTo('edit subscriptions');
    }

    public function delete(User $user, Subscription $subscription): bool
    {
        return $user->isAdmin() || $user->hasPermissionTo('delete subscriptions');
    }
}
