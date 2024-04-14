<?php

declare(strict_types=1);

namespace App\Scraper;

enum PaymentSystem: string
{
    case MASTERCARD = 'mastercard';
    case VISA = 'visa';
}
