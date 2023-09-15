<?php

namespace App\Service;

use App\Dto\TelegramResponse;
use App\Enum\Endpoint;

interface TelegramApiRequestSenderInterface
{
    public function setToken(string $token): void;

    /**
     * @param array<string, mixed> $params
     */
    public function send(Endpoint $endpoint, array $params = []): ?TelegramResponse;
}
