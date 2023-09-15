<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Dto\SendMessageRequestPayload;
use App\Dto\TelegramResponse;
use App\Enum\ParseMode;
use App\Service\TelegramApiInterface;
use App\Service\TelegramService;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

final class TelegramServiceTest extends TestCase
{
    private const TOKEN = 'test_token';
    private const CHAT_ID = 'test_chat_id';

    /**
     * @throws Exception
     */
    public function testGetMeSuccess(): void
    {
        $returnValue = [
            'test_key' => 'test_value',
        ];

        $telegramApiMock = $this->createMock(TelegramApiInterface::class);
        $telegramApiMock
            ->method('getMe')
            ->willReturn(
                new TelegramResponse([
                    'ok' => true,
                    'result' => $returnValue,
                ]),
            );

        $loggerMock = $this->createMock(LoggerInterface::class);

        $telegramService = new TelegramService($telegramApiMock, $loggerMock);

        $result = $telegramService->getMe(self::TOKEN);

        self::assertIsArray($result);
        self::assertSame($returnValue, $result);
    }

    /**
     * @throws Exception
     */
    public function testGetMeFailed(): void
    {
        $returnValue = null;

        $telegramApiMock = $this->createMock(TelegramApiInterface::class);
        $telegramApiMock
            ->method('getMe')
            ->willReturn(
                new TelegramResponse([
                    'ok' => false,
                    'result' => $returnValue,
                ]),
            );

        $loggerMock = $this->createMock(LoggerInterface::class);

        $telegramService = new TelegramService($telegramApiMock, $loggerMock);

        $result = $telegramService->getMe(self::TOKEN);

        self::assertNull($result);
    }

    /**
     * @throws Exception
     */
    public function testSendMessageSuccess(): void
    {
        $telegramApiMock = $this->createMock(TelegramApiInterface::class);
        $telegramApiMock
            ->method('sendMessage')
            ->willReturn(
                new TelegramResponse([
                    'ok' => true,
                    'result' => [
                        'test_key' => 'test_value',
                    ],
                ]),
            );

        $loggerMock = $this->createMock(LoggerInterface::class);

        $telegramService = new TelegramService($telegramApiMock, $loggerMock);

        $result = $telegramService->sendMessage(self::getTestDto());

        self::assertTrue($result);
    }

    /**
     * @throws Exception
     */
    public function testSendMessageFailed(): void
    {
        $telegramApiMock = $this->createMock(TelegramApiInterface::class);
        $telegramApiMock
            ->method('sendMessage')
            ->willReturn(
                new TelegramResponse([
                    'ok' => false,
                    'error_code' => 400,
                    'description' => 'Bad Request',
                ]),
            );

        $loggerMock = $this->createMock(LoggerInterface::class);

        $telegramService = new TelegramService($telegramApiMock, $loggerMock);

        $result = $telegramService->sendMessage(self::getTestDto());

        self::assertFalse($result);
    }

    private static function getTestDto(): SendMessageRequestPayload
    {
        $request = new Request();

        $request->request->set('token', self::TOKEN);
        $request->request->set('chat_id', self::CHAT_ID);
        $request->request->set('text', 'test');
        $request->request->set('parse_mode', ParseMode::MARKDOWN_V2->value);

        return SendMessageRequestPayload::createFromRequest($request);
    }
}
