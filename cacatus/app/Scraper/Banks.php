<?php

declare(strict_types=1);

namespace App\Scraper;

enum Banks: string
{
    case HALYK = 'halyk';
    case JUSAN = 'jusan';
    case FORTE = 'forte';
    case EURASIAN = 'eurasian';
}
