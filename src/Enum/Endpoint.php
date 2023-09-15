<?php

declare(strict_types=1);

namespace App\Enum;

enum Endpoint: string
{
    case GET_ME = 'getMe';
    case SEND_MESSAGE = 'sendMessage';
}
