<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\SendMessageRequestPayload;
use Psr\Log\LoggerInterface;

final class TelegramService implements TelegramServiceInterface
{
    public function __construct(
        private readonly TelegramApiInterface $telegramApi,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function getMe(string $token): ?array
    {
        $response = $this->telegramApi->getMe($token);

        if ($response === null || $response->ok === false) {
            $this->logger->error('Пустой ответ или некорректный статус');

            return null;
        }

        return $response->result;
    }

    public function sendMessage(SendMessageRequestPayload $sendMessageRequestPayload): bool
    {
        $response = $this->telegramApi->sendMessage($sendMessageRequestPayload);

        if ($response === null || $response->ok === false) {
            $this->logger->error('Пустой ответ или некорректный статус');

            return false;
        }

        return true;
    }
}
