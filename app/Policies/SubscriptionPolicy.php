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

    public function view(User $authenticatedUser, Subscription $subscription): bool
    {
        return $authenticatedUser->isAdmin() || $authenticatedUser->hasPermissionTo('view subscriptions') || $subscription->user_id === $authenticatedUser->id;
    }

    public function create(User $authenticatedUser): bool
    {
        return $authenticatedUser->isAdmin() || $authenticatedUser->hasPermissionTo('create subscriptions') || $authenticatedUser->id === request()->user_id;
    }

    public function update(User $authenticatedUser, Subscription $subscription): bool
    {
        return $authenticatedUser->isAdmin() || $authenticatedUser->hasPermissionTo('edit subscriptions') || ($authenticatedUser->id === $subscription->user_id && $authenticatedUser->id === request()->user_id);
    }

    public function delete(User $authenticatedUser, Subscription $subscription): bool
    {
        return $authenticatedUser->isAdmin() || $authenticatedUser->hasPermissionTo('delete subscriptions') || ($authenticatedUser->id === $subscription->user_id);
    }
}
