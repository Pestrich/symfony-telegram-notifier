<?php

declare(strict_types=1);

namespace App\Enum;

enum ParseMode: string
{
    case MARKDOWN_V2 = 'MarkdownV2';
    case HTML = 'HTML';
}
