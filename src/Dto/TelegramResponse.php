<?php

declare(strict_types=1);

namespace App\Dto;

final class TelegramResponse
{
    public readonly bool $ok;
    public readonly ?int $errorCode;
    public readonly ?string $description;

    /**
     * @var array<string, mixed>|null
     */
    public readonly ?array $result;

    public function __construct(
        array $response,
    ) {
        $this->ok = $response['ok'];
        $this->errorCode = $response['error_code'] ?? null;
        $this->description = $response['description'] ?? null;
        $this->result = $response['result'] ?? null;
    }
}
