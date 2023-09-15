<?php

declare(strict_types=1);

namespace App\Dto;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

final class GetMeRequestPayload
{
    private function __construct(
        public readonly string $token,
    ) {
    }

    public static function createFromRequest(Request $request): self
    {
        $token = $request->request->getString('token');

        self::validate($token);

        return new self($token);
    }

    private static function validate(?string $token): void
    {
        if (empty($token)) {
            throw new InvalidArgumentException('В запросе переданы некорректные параметры');
        }
    }
}
