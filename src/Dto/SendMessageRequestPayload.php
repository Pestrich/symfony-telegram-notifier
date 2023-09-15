<?php

declare(strict_types=1);

namespace App\Dto;

use App\Enum\ParseMode;
use InvalidArgumentException;
use JsonSerializable;
use Symfony\Component\HttpFoundation\Request;

final class SendMessageRequestPayload implements JsonSerializable
{
    private function __construct(
        public readonly string $token,
        public readonly string $chatId,
        public readonly string $text,
        public readonly ParseMode $parseMode,
    ) {
    }

    public static function createFromRequest(Request $request): self
    {
        $token = $request->request->getString('token');
        $chatId = $request->request->getString('chat_id');
        $text = $request->request->getString('text');
        $parseMode = $request->request->getString('parse_mode');

        self::validate($token, $chatId, $text, $parseMode);

        return new self($token, $chatId, $text, ParseMode::from($parseMode));
    }

    /**
     * @return non-empty-array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'chat_id' => $this->chatId,
            'text' => $this->text,
            'parse_mode' => $this->parseMode->value,
        ];
    }

    private static function validate(
        ?string $token,
        ?string $chatId,
        ?string $text,
        ?string $parseMode,
    ): void {
        if (
            empty($token) ||
            empty($chatId) ||
            empty($text) ||
            empty($parseMode) ||
            ParseMode::tryFrom($parseMode) === null
        ) {
            throw new InvalidArgumentException('В запросе переданы некорректные параметры');
        }
    }
}
