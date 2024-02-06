<?php

namespace App\Policies;

use App\Models\Package;
use App\Models\User;

class PackagePolicy
{
    public function viewAny(User $authenticatedUser): bool
    {
        return $authenticatedUser->isAdmin() || $authenticatedUser->hasPermissionTo('view any packages');
    }

    public function view(User $user, Package $package): bool
    {
        return $user->isAdmin() || $user->hasPermissionTo('view packages');
    }

    public function create(User $authenticatedUser): bool
    {
        return $authenticatedUser->isAdmin() || $authenticatedUser->hasPermissionTo('create packages');
    }

    public function update(User $authenticatedUser): bool
    {
        return $authenticatedUser->isAdmin() || $authenticatedUser->hasPermissionTo('edit packages');
    }

    public function delete(User $user, Package $package): bool
    {
        return $user->isAdmin() || $user->hasPermissionTo('delete packages');
    }

    public function attach(User $user): bool
    {
        return $user->isAdmin() || $user->hasPermissionTo('attach packages channels');
    }

    public function detach(User $user): bool
    {
        return $user->isAdmin() || $user->hasPermissionTo('detach packages channels');
    }
}
