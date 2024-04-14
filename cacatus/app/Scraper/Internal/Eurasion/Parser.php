<?php

declare(strict_types=1);

namespace App\Scraper\Internal\Eurasion;

use App\Scraper\Banks;
use App\Scraper\BankType;
use App\Scraper\Internal\ScraperInterface;
use App\Scraper\PaymentSystem;
use App\Scraper\Scrape;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\DomCrawler\Crawler;

final class Parser implements ScraperInterface
{
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
        $requests = array_map(function (string $url) {
            return new Request('GET', $url);
        }, $this->links);

        $results = [];

        $pool = new Pool($client, $requests, [
            'concurrency' => 4,
            'fulfilled' => function (Response $response, int $index) use (&$results) {
                $results[$index] = $response;
            }
        ]);

        $promise = $pool->promise();
        $promise->wait();

        return $this->dispatchResults($results);
    }

    /**
     * @param Response[]
     * @return \App\Scraper\Scrape[]
     */
    private function dispatchResults(array $results): array
    {
        /**
         * @var \App\Scraper\Scrape[]
         */
        $res = [];
        foreach($results as $index => $result) {
            $url = $this->links[$index];
            $crawler = new Crawler($result->getBody()->getContents());
            try {
                $date = Carbon::parse(substr(str_replace("/", '', $url), -10), 'Asia/Almaty')->timestamp;
            } catch (\Throwable $th) {
                $date = null;
            }

            try {
                $title = $crawler
                ->filter('h2.elementor-heading-title.elementor-size-default')
                ->first()
                ->text();
            } catch (\Throwable $th) {
                $title = $crawler->filter('title')->innerText();
            }

            $res[] = (new Scrape(
                hash: hash('sha256', $url),
                bankType: new BankType(Banks::EURASIAN, [
                    PaymentSystem::MASTERCARD,
                    PaymentSystem::VISA
                ]),
                raw: $crawler
                    ->filter('div.elementor-text-editor.elementor-clearfix')
                    ->first()
                    ->text(),
                title: $title,
                url: $url,
                cashback: 0,
                image_url: $crawler
                    ->filter('div.elementor-image')
                    ->first()
                    ->filter('img')
                    ->count() > 0
                        ? $crawler
                        ->filter('div.elementor-image')
                        ->first()
                        ->filter('img')
                        ->first()
                        ->attr('href')
                    : null,
                createdAt: $date,
                timeStart: $date,
            ));
        }

        return $res;
    }
}
