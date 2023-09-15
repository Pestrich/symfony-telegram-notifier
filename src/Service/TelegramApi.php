<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\SendMessageRequestPayload;
use App\Dto\TelegramResponse;
use App\Enum\Endpoint;

final class TelegramApi implements TelegramApiInterface
{
    public function __construct(
        private readonly TelegramApiRequestSenderInterface $telegramApiRequestSender,
    ) {
    }

    public function getMe(string $token): ?TelegramResponse
    {
        $this->telegramApiRequestSender->setToken($token);

        return $this->telegramApiRequestSender->send(Endpoint::GET_ME);
    }

    public function sendMessage(SendMessageRequestPayload $sendMessageRequestPayload): ?TelegramResponse
    {
        $this->telegramApiRequestSender->setToken($sendMessageRequestPayload->token);

        $params = $sendMessageRequestPayload->jsonSerialize();

        return $this->telegramApiRequestSender->send(Endpoint::SEND_MESSAGE, $params);
    }
}
