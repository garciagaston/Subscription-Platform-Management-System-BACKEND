<?php

namespace App\Helpers;

use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

final class TestsHelper
{
    public const string COMMAND_TYPE_TEST = 'test';

    public const string PASSWORD = 'password';

    public static function dumpApiResponsesWithErrors(TestResponse $response, int $status = Response::HTTP_OK)
    {
        if ($response->getStatusCode() != $status) {
            echo 'Failed test method: '.debug_backtrace()[1]['function']." | Status response: {$response->getStatusCode()} \n";
            $response->dump();
        }
    }

    public static function isLocalHost(): bool
    {
        return strtolower(env('APP_ENV')) === 'local';
    }

    public static function checkPagination(TestResponse $response, TestCase $test, int $count, int $perPage): void
    {
        $response->assertJsonStructure(
            [
                'data',
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next',
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'links',
                    'path',
                    'per_page',
                    'to',
                    'total',
                ],
            ]
        );
        $jsonResponse = json_decode($response->getContent());
        $test->assertEquals(count($jsonResponse->data), min($count, $perPage));
        $test->assertEquals($jsonResponse->meta->per_page, $perPage);
        $test->assertEquals($jsonResponse->meta->from, 1);
        $test->assertEquals($jsonResponse->meta->last_page, max(ceil($count / $perPage), 1));
        $test->assertEquals($jsonResponse->meta->total, $count);
        $test->assertNotNull($jsonResponse->links->first);
        $test->assertNotNull($jsonResponse->links->last);
        $test->assertNull($jsonResponse->links->prev);
        if ($count > $perPage) {
            $test->assertNotNull($jsonResponse->links->next);
        } else {
            $test->assertNull($jsonResponse->links->next);
        }
    }

    public static function getJsonResponse(TestResponse $response): mixed
    {
        return json_decode($response->getContent(), true);
    }
}
