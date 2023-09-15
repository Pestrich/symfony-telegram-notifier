<?php

declare(strict_types=1);

namespace App\Tests\Unit\Dto;

use App\Dto\SendMessageRequestPayload;
use App\Enum\ParseMode;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class SendMessageRequestPayloadTest extends TestCase
{
    private const TOKEN = 'test_token';
    private const CHAT_ID = 'test_chat_id';

    public static function correctRequestDataProvider(): iterable
    {
        yield ["\n\n_test1_", ParseMode::MARKDOWN_V2];
        yield ["\n\n<p>test2</p>", ParseMode::HTML];
    }

    /**
     * @dataProvider correctRequestDataProvider
     */
    public function testCreateFromRequestWithCorrectData(
        string $text,
        ParseMode $parseMode,
    ): void {
        $request = new Request();

        $request->request->set('token', self::TOKEN);
        $request->request->set('chat_id', self::CHAT_ID);
        $request->request->set('text', $text);
        $request->request->set('parse_mode', $parseMode->value);

        $sendMessageRequestPayload = SendMessageRequestPayload::createFromRequest($request);

        self::assertSame(self::TOKEN, $sendMessageRequestPayload->token);
        self::assertSame(self::CHAT_ID, $sendMessageRequestPayload->chatId);
        self::assertSame($text, $sendMessageRequestPayload->text);
        self::assertSame($parseMode->value, $sendMessageRequestPayload->parseMode->value);

        $messageParams = $sendMessageRequestPayload->jsonSerialize();

        self::assertIsArray($messageParams);
        self::assertArrayHasKey('chat_id', $messageParams);
        self::assertArrayHasKey('text', $messageParams);
        self::assertArrayHasKey('parse_mode', $messageParams);
        self::assertSame(self::CHAT_ID, $messageParams['chat_id']);
        self::assertSame($text, $messageParams['text']);
        self::assertSame($parseMode->value, $messageParams['parse_mode']);
    }

    public function testCreateFromRequestWithoutData(): void
    {
        $request = new Request();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('В запросе переданы некорректные параметры');

        SendMessageRequestPayload::createFromRequest($request);
    }

    public function testCreateFromRequestWithIncorrectParseMode(): void
    {
        $request = new Request();

        $request->request->set('token', self::TOKEN);
        $request->request->set('chat_id', self::CHAT_ID);
        $request->request->set('text', 'test');
        $request->request->set('parse_mode', 'Test');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('В запросе переданы некорректные параметры');

        SendMessageRequestPayload::createFromRequest($request);
    }

    public function testCreateFromRequestWithEmptyToken(): void
    {
        $request = new Request();

        $request->request->set('token', null);
        $request->request->set('chat_id', self::CHAT_ID);
        $request->request->set('text', 'test');
        $request->request->set('parse_mode', ParseMode::MARKDOWN_V2->value);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('В запросе переданы некорректные параметры');

        SendMessageRequestPayload::createFromRequest($request);
    }

    public function testCreateFromRequestWithEmptyChatId(): void
    {
        $request = new Request();

        $request->request->set('token', self::TOKEN);
        $request->request->set('chat_id', null);
        $request->request->set('text', 'test');
        $request->request->set('parse_mode', ParseMode::MARKDOWN_V2->value);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('В запросе переданы некорректные параметры');

        SendMessageRequestPayload::createFromRequest($request);
    }

    public function testCreateFromRequestWithEmptyText(): void
    {
        $request = new Request();

        $request->request->set('token', self::TOKEN);
        $request->request->set('chat_id', self::CHAT_ID);
        $request->request->set('text', null);
        $request->request->set('parse_mode', ParseMode::MARKDOWN_V2->value);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('В запросе переданы некорректные параметры');

        SendMessageRequestPayload::createFromRequest($request);
    }

    public function testCreateFromRequestWithEmptyParseMode(): void
    {
        $request = new Request();

        $request->request->set('token', self::TOKEN);
        $request->request->set('chat_id', self::CHAT_ID);
        $request->request->set('text', 'test');
        $request->request->set('parse_mode', null);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('В запросе переданы некорректные параметры');

        SendMessageRequestPayload::createFromRequest($request);
    }
}
