<?php

declare(strict_types=1);

namespace App\Scraper\Internal\Halyk;

use App\Scraper\Banks;
use App\Scraper\BankType;
use App\Scraper\Internal\ScraperInterface;
use App\Scraper\PaymentSystem;
use App\Scraper\Scrape;
use Carbon\Carbon;
use DateTimeZone;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;
use Symfony\Component\DomCrawler\Crawler;

final class Parser implements ScraperInterface
{
    /**
     * @param array[string] $links
     */
    public function __construct(
        private array $links
    ) {
    }

    /**
     * @inheritdoc
     */
    public function scrape(): array
    {
        $client = new Client(['verify' => false]);
        $promises = [];
        foreach($this->links as $link) {
            $promises[] = $client->requestAsync("GET", $link);
        }

        /**
         * @var array<array{'state':string, 'value':\GuzzleHttp\Psr7\Response}>
         */
        $results = Utils::settle($promises)->wait(true);

        return $this->dispatchResults($results);
    }

    private function getDates(string $line): array
    {
        $pattern = '/(\d{2}\.\d{2}\.\d{4})/';
        preg_match_all($pattern, $line, $matches);
        $fromDate = isset($matches[0][0]) ? Carbon::parse($matches[0][0], "Asia/Almaty")->timestamp : null;
        $toDate = isset($matches[0][0]) ? Carbon::parse($matches[0][1], "Asia/Almaty")->timestamp : null;

        return [
            $fromDate,
            $toDate
        ];
    }

    private function getCashback(string $text): int
    {
        preg_match_all("/\d+(?:\.\d+)?%/", $text, $matches);
        // dump($matches);
        return isset($matches[0][0]) ? (int) $matches[0][0] : 0;
    }

    /**
     * @param array<array{'state':string, 'value':\GuzzleHttp\Psr7\Response}> $results
     * @return \App\Scraper\Scrape[]
     */
    private function dispatchResults(array $results): array
    {
        /**
         * @var \App\Scraper\Scrape[]
         */
        $res = [];

        foreach($results as $index => $result) {
            if($result['state'] !== 'fulfilled') {
                continue;
            }

            $crawler = new Crawler($result['value']->getBody()->getContents());
            $body = $crawler->filter('div.mb-10:not(.breadcrumbs)')->first();
            $bodyText = $body->text();
            $times = $this->getDates($body->filter('p')->eq(1)->text());
            $cashbak = $this->getCashback($bodyText);
            $res[] = (
                new Scrape(
                    hash: hash('sha256', $this->links[$index]),
                    bankType: new BankType(Banks::HALYK, [
                        PaymentSystem::MASTERCARD,
                        PaymentSystem::VISA
                    ]),
                    cashback: $cashbak,
                    raw: $bodyText,
                    title: $crawler->filter('h3.text-2xl.mb-6.font-semibold')->first()->text(),
                    url: $this->links[$index],
                    image_url: $crawler->filter('div.lazy.absolute.inset-0.bg-center.img.bg-contain._js-bvi-img')
                        ?->first()
                        ->attr('data-background-image'),
                    limitation: $body->filter('ul')->count() > 1 ? $body->filter('ul')->eq(1)->text() : null,
                    condition: $body->filter('ul')->count() > 0 ? $body->filter('ul')->eq(0)->text() : null,
                    createdAt: Carbon::parse($crawler->filter('div.mb-6.text-gray-400')->first()->text(), "Asia/Almaty")->timestamp,
                    timeEnd: $times[1] ?? null,
                    timeStart: $times[0] ?? null,
                )
            );
        }

        return $res;
    }
}
