<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Dto\SendMessageRequestPayload;
use App\Dto\TelegramResponse;
use App\Enum\ParseMode;
use App\Service\TelegramApi;
use App\Service\TelegramApiRequestSenderInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class TelegramApiTest extends TestCase
{
    private const TOKEN = 'test_token';
    private const CHAT_ID = 'test_chat_id';

    /**
     * @throws Exception
     */
    public function testGetMeSuccess(): void
    {
        $returnValue = new TelegramResponse([
            'ok' => true,
            'result' => [
                'test_key' => 'test_value',
            ],
        ]);

        $telegramApiRequestSenderMock = $this->createMock(TelegramApiRequestSenderInterface::class);
        $telegramApiRequestSenderMock
            ->method('send')
            ->willReturn($returnValue);

        $telegramApi = new TelegramApi($telegramApiRequestSenderMock);

        $result = $telegramApi->getMe(self::TOKEN);

        self::assertSame($returnValue, $result);
    }

    /**
     * @throws Exception
     */
    public function testGetMeFailed(): void
    {
        $telegramApiRequestSenderMock = $this->createMock(TelegramApiRequestSenderInterface::class);
        $telegramApiRequestSenderMock
            ->method('send')
            ->willReturn(null);

        $telegramApi = new TelegramApi($telegramApiRequestSenderMock);

        $result = $telegramApi->getMe(self::TOKEN);

        self::assertNull($result);
    }

    /**
     * @throws Exception
     */
    public function testSendMessageSuccess(): void
    {
        $returnValue = new TelegramResponse([
            'ok' => true,
            'result' => [
                'test_key' => 'test_value',
            ],
        ]);

        $telegramApiRequestSenderMock = $this->createMock(TelegramApiRequestSenderInterface::class);
        $telegramApiRequestSenderMock
            ->method('send')
            ->willReturn($returnValue);

        $telegramApi = new TelegramApi($telegramApiRequestSenderMock);

        $result = $telegramApi->sendMessage(self::getTestDto());

        self::assertSame($returnValue, $result);
    }

    /**
     * @throws Exception
     */
    public function testSendMessageFailed(): void
    {
        $telegramApiRequestSenderMock = $this->createMock(TelegramApiRequestSenderInterface::class);
        $telegramApiRequestSenderMock
            ->method('send')
            ->willReturn(null);

        $telegramApi = new TelegramApi($telegramApiRequestSenderMock);

        $result = $telegramApi->sendMessage(self::getTestDto());

        self::assertNull($result);
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
