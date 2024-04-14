<?php

declare(strict_types=1);

namespace App\Scraper;

enum Category: string
{
    case FURNITURE = 'furniture';
    case ELECTRONICS = 'electronics';
    case FUEL = 'fuel';
    case GROCERY = 'grocery';
    case CLOTHING = 'clothing';
    case GRADES = 'grades';
    case BANKING = 'banking';
    case AUTO = 'auto';
    case APPLIANCES = 'appliances'; //bytovaya tehnika
    case OTHER = 'other';
}
