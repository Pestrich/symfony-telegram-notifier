<?php

declare(strict_types=1);

namespace App\Tests\Unit\Utils;

use App\Utils\ApiResponseBody;
use PHPUnit\Framework\TestCase;

final class ApiResponseBodyTest extends TestCase
{
    public function testSetSuccess(): void
    {
        $responseBody = new ApiResponseBody();

        $responseBody->setSuccess(true);

        $response = $responseBody->jsonSerialize();

        self::assertIsArray($response);
        self::assertArrayHasKey('success', $response);
        self::assertArrayHasKey('errors', $response);
        self::assertArrayHasKey('payload', $response);
        self::assertTrue($response['success']);
        self::assertIsArray($response['errors']);
        self::assertEmpty($response['errors']);
        self::assertNull($response['payload']);
    }

    public function testSetError(): void
    {
        $responseBody = new ApiResponseBody();

        $responseBody->setError('Test error message');

        $response = $responseBody->jsonSerialize();

        self::assertIsArray($response);
        self::assertArrayHasKey('success', $response);
        self::assertArrayHasKey('errors', $response);
        self::assertArrayHasKey('payload', $response);
        self::assertFalse($response['success']);
        self::assertIsArray($response['errors']);
        self::assertNotEmpty($response['errors']);
        self::assertSame('Test error message', $response['errors'][0]);
        self::assertNull($response['payload']);
    }

    public function testSetSuccessAndPayload(): void
    {
        $responseBody = new ApiResponseBody();

        $responseBody->setSuccess(true);
        $responseBody->setPayload([
            'is_test' => true,
        ]);

        $response = $responseBody->jsonSerialize();

        self::assertIsArray($response);
        self::assertArrayHasKey('success', $response);
        self::assertArrayHasKey('errors', $response);
        self::assertArrayHasKey('payload', $response);
        self::assertTrue($response['success']);
        self::assertIsArray($response['errors']);
        self::assertEmpty($response['errors']);
        self::assertIsArray($response['payload']);
        self::assertNotEmpty($response['payload']);
        self::assertArrayHasKey('is_test', $response['payload']);
        self::assertTrue($response['payload']['is_test']);
    }
}
