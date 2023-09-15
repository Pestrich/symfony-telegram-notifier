<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\SendMessageRequestPayload;
use App\Dto\TelegramResponse;

interface TelegramApiInterface
{
    public function getMe(string $token): ?TelegramResponse;

    public function sendMessage(SendMessageRequestPayload $sendMessageRequestPayload): ?TelegramResponse;
}
