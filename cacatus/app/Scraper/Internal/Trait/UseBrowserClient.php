<?php

declare(strict_types=1);

namespace App\Scraper\Internal\Trait;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Panther\Client;

trait UseBrowserClient
{
    private ?Client $client = null;

    private function get(string $url, string $waitfor): Crawler
    {
        if($this->client === null) {
            $this->client = Client::createChromeClient();
        }
        $client = $this->client;

        $crawler = new Crawler();
        $crawler->add($client->get($url)->waitFor($waitfor)->html());
        return $crawler;
    }
}
