<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Enum\Endpoint;
use App\Service\TelegramApiRequestFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class TelegramApiRequestFactoryTest extends TestCase
{
    public static function endpointProvider(): iterable
    {
        yield [Endpoint::GET_ME];
        yield [Endpoint::SEND_MESSAGE];
    }

    /**
     * @dataProvider endpointProvider
     */
    public function testCreate(Endpoint $endpoint): void
    {
        $request = TelegramApiRequestFactory::create($endpoint);

        self::assertSame(Request::METHOD_POST, $request->getMethod());
        self::assertSame($endpoint->value, $request->getUri()->getPath());
    }
}
