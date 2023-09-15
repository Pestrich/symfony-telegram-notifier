<?php

declare(strict_types=1);

namespace App\Utils;

use JsonSerializable;

final class ApiResponseBody implements JsonSerializable
{
    private bool $success = false;
    private mixed $payload = null;

    /**
     * @var array<string>
     */
    private array $errors = [];

    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    public function setPayload(mixed $payload): void
    {
        $this->payload = $payload;
    }

    public function setError(string $errorMessage): void
    {
        $this->errors[] = $errorMessage;
    }

    /**
     * @return non-empty-array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'success' => $this->success,
            'payload' => $this->payload,
            'errors' => $this->errors,
        ];
    }
}
