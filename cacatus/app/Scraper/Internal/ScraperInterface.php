<?php

declare(strict_types=1);

namespace App\Scraper\Internal;

interface ScraperInterface
{
    /**
     * @return \App\Scraper\Scrape[]
     */
    public function scrape(): array;
}
