<?php

namespace App\Providers;

use App\Models\Channel;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\User;
use App\Policies\ChannelPolicy;
use App\Policies\PackagePolicy;
use App\Policies\SubscriptionPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Subscription::class => SubscriptionPolicy::class,
        Package::class => PackagePolicy::class,
        Channel::class => ChannelPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
