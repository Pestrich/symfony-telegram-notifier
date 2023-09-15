<?php

declare(strict_types=1);

namespace App\Tests\Unit\Controller;

use App\Controller\TelegramController;
use App\Enum\ParseMode;
use App\Service\TelegramServiceInterface;
use App\Utils\ApiResponseBody;
use JsonException;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

use function json_encode;

use const JSON_THROW_ON_ERROR;

final class TelegramControllerTest extends TestCase
{
    private const TOKEN = 'test_token';
    private const CHAT_ID = 'test_chat_id';

    /**
     * @throws Exception
     * @throws JsonException
     */
    public function testGetMeSuccess(): void
    {
        $returnValue = [
            'test_key' => 'test_value',
        ];

        $telegramServiceMock = $this->createMock(TelegramServiceInterface::class);
        $telegramServiceMock
            ->method('getMe')
            ->willReturn($returnValue);

        $loggerMock = $this->createMock(LoggerInterface::class);

        $telegramController = new TelegramController($telegramServiceMock, new ApiResponseBody(), $loggerMock);

        $result = $telegramController->getMe(self::getTestRequestForGetMe());

        $responseBody = new ApiResponseBody();
        $responseBody->setSuccess(true);
        $responseBody->setPayload($returnValue);

        $content = json_encode($responseBody->jsonSerialize(), JSON_THROW_ON_ERROR);

        self::assertSame(200, $result->getStatusCode());
        self::assertSame($content, $result->getContent());
    }

    /**
     * @throws Exception
     * @throws JsonException
     */
    public function testGetMeFailed(): void
    {
        $returnValue = null;

        $telegramServiceMock = $this->createMock(TelegramServiceInterface::class);
        $telegramServiceMock
            ->method('getMe')
            ->willReturn($returnValue);

        $loggerMock = $this->createMock(LoggerInterface::class);

        $telegramController = new TelegramController($telegramServiceMock, new ApiResponseBody(), $loggerMock);

        $result = $telegramController->getMe(self::getTestRequestForGetMe());

        $responseBody = new ApiResponseBody();
        $responseBody->setSuccess(false);

        $content = json_encode($responseBody->jsonSerialize(), JSON_THROW_ON_ERROR);

        self::assertSame(200, $result->getStatusCode());
        self::assertSame($content, $result->getContent());
    }

    /**
     * @throws Exception
     * @throws JsonException
     */
    public function testGetMeException(): void
    {
        $returnValue = null;

        $telegramServiceMock = $this->createMock(TelegramServiceInterface::class);
        $telegramServiceMock
            ->method('getMe')
            ->willReturn($returnValue);

        $loggerMock = $this->createMock(LoggerInterface::class);

        $telegramController = new TelegramController($telegramServiceMock, new ApiResponseBody(), $loggerMock);

        $result = $telegramController->getMe(new Request());

        $responseBody = new ApiResponseBody();
        $responseBody->setSuccess(false);
        $responseBody->setError('В запросе переданы некорректные параметры');

        $content = json_encode($responseBody->jsonSerialize(), JSON_THROW_ON_ERROR);

        self::assertSame(200, $result->getStatusCode());
        self::assertSame($content, $result->getContent());
    }

    /**
     * @throws Exception
     * @throws JsonException
     */
    public function testSendMessageSuccess(): void
    {
        $telegramServiceMock = $this->createMock(TelegramServiceInterface::class);
        $telegramServiceMock
            ->method('sendMessage')
            ->willReturn(true);

        $loggerMock = $this->createMock(LoggerInterface::class);

        $telegramController = new TelegramController($telegramServiceMock, new ApiResponseBody(), $loggerMock);

        $result = $telegramController->sendMessage(self::getTestRequestForSendMessage());

        $responseBody = new ApiResponseBody();
        $responseBody->setSuccess(true);

        $content = json_encode($responseBody->jsonSerialize(), JSON_THROW_ON_ERROR);

        self::assertSame(200, $result->getStatusCode());
        self::assertSame($content, $result->getContent());
    }

    /**
     * @throws Exception
     * @throws JsonException
     */
    public function testSendMessageFailed(): void
    {
        $telegramServiceMock = $this->createMock(TelegramServiceInterface::class);
        $telegramServiceMock
            ->method('sendMessage')
            ->willReturn(false);

        $loggerMock = $this->createMock(LoggerInterface::class);

        $telegramController = new TelegramController($telegramServiceMock, new ApiResponseBody(), $loggerMock);

        $result = $telegramController->sendMessage(self::getTestRequestForSendMessage());

        $responseBody = new ApiResponseBody();
        $responseBody->setSuccess(false);

        $content = json_encode($responseBody->jsonSerialize(), JSON_THROW_ON_ERROR);

        self::assertSame(200, $result->getStatusCode());
        self::assertSame($content, $result->getContent());
    }

    /**
     * @throws Exception
     * @throws JsonException
     */
    public function testSendMessageException(): void
    {
        $telegramServiceMock = $this->createMock(TelegramServiceInterface::class);
        $telegramServiceMock
            ->method('sendMessage')
            ->willReturn(false);

        $loggerMock = $this->createMock(LoggerInterface::class);

        $telegramController = new TelegramController($telegramServiceMock, new ApiResponseBody(), $loggerMock);

        $result = $telegramController->sendMessage(new Request());

        $responseBody = new ApiResponseBody();
        $responseBody->setSuccess(false);
        $responseBody->setError('В запросе переданы некорректные параметры');

        $content = json_encode($responseBody->jsonSerialize(), JSON_THROW_ON_ERROR);

        self::assertSame(200, $result->getStatusCode());
        self::assertSame($content, $result->getContent());
    }

    private static function getTestRequestForGetMe(): Request
    {
        $request = new Request();

        $request->request->set('token', self::TOKEN);

        return $request;
    }

    private static function getTestRequestForSendMessage(): Request
    {
        $request = new Request();

        $request->request->set('token', self::TOKEN);
        $request->request->set('chat_id', self::CHAT_ID);
        $request->request->set('text', 'test');
        $request->request->set('parse_mode', ParseMode::MARKDOWN_V2->value);

        return $request;
    }
}
