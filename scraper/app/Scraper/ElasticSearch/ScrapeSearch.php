<?php

declare(strict_types=1);

namespace App\Scraper\ElasticSearch;

use App\Scraper\Scrape;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;

class ScrapeSearch
{
    private Client $elasticClient;
    public const INDEX = 'scrapes';

    public function __construct(string $url)
    {
        $this->elasticClient = ClientBuilder::create()
            ->setHosts([$url])
            ->build();
    }

    /**
     * @param Scrape[]
     * @return void
     */
    public function bulkPush(array $scrapes): void
    {
        $params = [];
        foreach($scrapes as $scrape) {
            $params['body'][] = [
                'index' => [
                    '_index' => static::INDEX,
                    '_id' => $scrape->hash
                ]
            ];

            $params['body'][] = $scrape->toArray();
        }

        $this->elasticClient->bulk($params);
    }

    public function pushScrape(Scrape $scrape): void
    {
        $params = [
            'index' => static::INDEX,
            'id' => $scrape->hash,
            'body' => $scrape->toArray()
        ];

        $this->elasticClient->index($params);
    }

    // {
    //     "query": {
    //       "bool": {
    //         "must": [
    //           {
    //             "match": {
    //               "raw": {
    //                 "query": "visa",
    //                 "operator": "or",
    //                 "fuzziness": 1.0,
    //                 "prefix_length": 1
    //               }
    //             }
    //           },
    //           {
    //             "match": {
    //               "bank_type.bank": {
    //                 "query": "eurasian",
    //                 "operator": "or",
    //                 "fuzziness": 1.0,
    //                 "prefix_length": 1
    //               }
    //             }
    //           }
    //         ]
    //       }
    //     }
    //   }

    /**
     * @param string $textQuery
     * @return array{int, string[]}
     */
    public function searchFullText(string $textQuery, null|array|string $bank = null, ?string $category = null)
    {
        $json = ['query' => [
            "bool" => [
                "must" => []
            ]
        ]];

        $json['query']['bool']['must'][] = [
            "match" => [
                "raw" => [
                    'query' => $textQuery,
                    "operator" => "or",
                    "fuzziness" => 1.0,
                    "prefix_length" => 1
                ]
            ]
        ];

        if($bank) {
            if(is_string($bank)) {
                $bank = [$bank];
            }
            $json['query']['bool']['must'][] = [
                "terms" => [
                    "bank_type.bank" => $bank
                ]
            ];
        }

        if($category) {
            $json['query']['bool']['must'][] = [
                "match" => [
                    "category" => [
                        'query' => $category,
                        "operator" => "or",
                        "fuzziness" => 1.0,
                        "prefix_length" => 1
                    ]
                ]
            ];
        }

        $params = [
            'index' => static::INDEX,
            'body' => $json
        ];

        $res = $this->elasticClient->search($params);
        return [
            count($res['hits']['hits']),
            array_map(function (array $hit) {
                return $hit['_id'];
            }, $res['hits']['hits'])
        ];
    }

    public function search(
        ?string $textQuery = null,
        ?string $bank = null,
        ?string $paymentSystems = null
    ) {
        $json = ['query' => []];
        if($textQuery) {
            $json['query']['match']['raw'] = [
                'query' => $textQuery,
                "operator" => "or",
                "fuzziness" => 1.0,
                "prefix_length" => 1
            ];
        }
        $params = [];
    }
}
