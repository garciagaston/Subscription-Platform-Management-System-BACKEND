<?php
namespace App\Helpers;

use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;

final class TestsHelper
{
    public static function dumpApiResponsesWithErrors(TestResponse $response, int $status = Response::HTTP_OK)
    {
        if ($response->getStatusCode() != $status) {
            echo "Failed test method: " . debug_backtrace()[1]['function'] . " | Status response: {$response->getStatusCode()} \n";
            $response->dump();
        }
    }
}
