<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\Endpoint;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Symfony\Component\HttpFoundation\Request;

final class TelegramApiRequestFactory
{
    public static function create(Endpoint $endpoint): GuzzleRequest
    {
        return new GuzzleRequest(Request::METHOD_POST, $endpoint->value);
    }
}
