<?php
namespace App\Helpers;

use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;
use Illuminate\Contracts\Foundation\Application;
use NunoMaduro\LaravelDesktopNotifier\Notification;

final class TestsHelper
{
    public const COMMAND_TYPE_TEST = 'test';

    public static function dumpApiResponsesWithErrors(TestResponse $response, int $status = Response::HTTP_OK)
    {
        if ($response->getStatusCode() != $status) {
            echo "Failed test method: " . debug_backtrace()[1]['function'] . " | Status response: {$response->getStatusCode()} \n";
            $response->dump();
        }
    }

    public static function isLocalHost(): bool
    {
        return strtolower(env('APP_ENV')) === 'local';
    }

    public static function notifyTests(bool $passed = true, Application $app = null): void
    {
        $notification = (new Notification())
            ->setTitle(config('app.name') . ' Tests')
            ->setBody(
                $passed ?
                    'All tests passed!' :
                    'One or more tests failed!'
            )
            ->addOption( // ignore this part if you don't use a Mac
                'sound',
                $passed ?
                    'glass' :
                    'basso'
            );
        $app->make('desktop.notifier')->send($notification);
    }
}
