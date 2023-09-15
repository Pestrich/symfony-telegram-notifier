<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\SendMessageRequestPayload;

interface TelegramServiceInterface
{
    /**
     * @return array<string, mixed>|null
     */
    public function getMe(string $token): ?array;

    public function sendMessage(SendMessageRequestPayload $sendMessageRequestPayload): bool;
}
