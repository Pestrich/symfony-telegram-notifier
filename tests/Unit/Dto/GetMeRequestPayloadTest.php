<?php

declare(strict_types=1);

namespace App\Tests\Unit\Dto;

use App\Dto\GetMeRequestPayload;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class GetMeRequestPayloadTest extends TestCase
{
    private const TOKEN = 'test_token';

    public function testCreateFromRequestWithCorrectData(): void
    {
        $request = new Request();

        $request->request->set('token', self::TOKEN);

        $getMeRequestPayload = GetMeRequestPayload::createFromRequest($request);

        self::assertSame(self::TOKEN, $getMeRequestPayload->token);
    }

    public function testCreateFromRequestWithoutData(): void
    {
        $request = new Request();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('В запросе переданы некорректные параметры');

        GetMeRequestPayload::createFromRequest($request);
    }

    public function testCreateFromRequestWithEmptyToken(): void
    {
        $request = new Request();

        $request->request->set('token', null);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('В запросе переданы некорректные параметры');

        GetMeRequestPayload::createFromRequest($request);
    }
}
