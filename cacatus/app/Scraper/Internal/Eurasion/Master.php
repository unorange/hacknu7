<?php

declare(strict_types=1);

namespace App\Scraper\Internal\Eurasion;

use App\Scraper\Internal\Trait\UseGuzzleClient;
use Symfony\Component\DomCrawler\Crawler;

class Master
{
    use UseGuzzleClient;

    /**
     * @return string[]
     */
    public function getLinks(): array
    {
        /**
         * @var Crawler[]
         */
        $crawlers = [];
        $i = 1;
        do {
            $crawler = $this->get("https://eubank.kz/promotions/" . $i);
            $crawlers[] = $crawler;
            $i++;
        } while($crawler->filter('article')->count() > 0);

        /**
         * @var string[]
         */
        $links = [];

        foreach($crawlers as $crawler) {
            $links = array_merge($links, $crawler->filter('article')->each(function (Crawler $node) {
                return $node->filter('a.elementor-post__read-more')->first()->attr('href');
            }));
        }

        return $links;
    }


}
