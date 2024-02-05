<?php

namespace App\Providers;

use App\Helpers\TestsHelper;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Console\Events\CommandFinished;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (TestsHelper::isLocalHost()) {
            Event::listen(CommandFinished::class, function (CommandFinished $event) {
                if ($event->command === TestsHelper::COMMAND_TYPE_TEST) {
                    $passed = !$event->exitCode;
                    TestsHelper::notifyTests($passed, $this->app);
                }
            });
        }    }
}
