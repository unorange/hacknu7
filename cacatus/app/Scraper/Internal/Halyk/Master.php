<?php

declare(strict_types=1);

namespace App\Scraper\Internal\Halyk;

use App\Scraper\Internal\Trait\UseGuzzleClient;
use Symfony\Component\DomCrawler\Crawler;

class Master
{
    use UseGuzzleClient;

    /**
     * @return string[]
     */
    public function parseLinks(): array
    {
        return $this
            ->get("https://halykbank.kz/promo")
            ->filter(
                'a.h-full.flex.flex-col.rounded-xl.border.border-gray-100.bg-gray-50'
            )->each(function (Crawler $node) {
                return $node->attr('href');
            });
    }
}
