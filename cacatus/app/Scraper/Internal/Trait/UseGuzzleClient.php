<?php

declare(strict_types=1);

namespace App\Scraper\Internal\Trait;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

trait UseGuzzleClient
{
    private ?Client $client = null;

    private function get(string $url): Crawler
    {
        if($this->client === null) {
            $this->client = new Client(['verify' => false]);
        }

        $client = $this->client;
        return new Crawler(
            $client
            ->get($url)
            ->getBody()
            ->getContents()
        );
    }
}
