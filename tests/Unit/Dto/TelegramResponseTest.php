<?php

declare(strict_types=1);

namespace App\Tests\Unit\Dto;

use App\Dto\TelegramResponse;
use PHPUnit\Framework\TestCase;

final class TelegramResponseTest extends TestCase
{
    public function testSuccessResponse(): void
    {
        $telegramResponse = [
            'ok' => true,
            'result' => [
                'test_key' => 'test_value'
            ],
        ];

        $response = new TelegramResponse($telegramResponse);

        self::assertTrue($response->ok);
        self::assertNull($response->errorCode);
        self::assertNull($response->description);
        self::assertIsArray($response->result);
        self::assertNotEmpty($response->result);
    }

    public function testFailedResponse(): void
    {
        $telegramResponse = [
            'ok' => false,
            'error_code' => 400,
            'description' => 'Bad Request',
        ];

        $response = new TelegramResponse($telegramResponse);

        self::assertFalse($response->ok);
        self::assertIsInt($response->errorCode);
        self::assertSame(400, $response->errorCode);
        self::assertIsString($response->description);
        self::assertSame($telegramResponse['description'], $response->description);
        self::assertNull($response->result);
    }
}
