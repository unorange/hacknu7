<?php

namespace App\Jobs;

use App\Models\Scrape;
use App\Scraper\ElasticSearch\ScrapeSearch;
use App\Scraper\Internal\Halyk\Master;
use App\Scraper\Internal\Halyk\Parser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class HalykParser implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle(ScrapeSearch $scrapeSearch): void
    {
        $halykScrapes = (new Parser((new Master())->parseLinks()))->scrape();
        $scrapesInsert = [];
        $scrapePaymentsInsert = [];
        foreach($halykScrapes as $halykScrape) {
            $scrapesInsert[] = [
                'hash' => $halykScrape->hash,
                'bank' => $halykScrape->bankType->bank->value,
                'cashback' => $halykScrape->cashback,
                'raw' => $halykScrape->raw,
                'title' => $halykScrape->title,
                'url' => $halykScrape->url,
                'image_url' => $halykScrape->image_url,
                'limitation' => $halykScrape->limitation,
                'condition' => $halykScrape->condition,
                'category' => $halykScrape->category,
                'franchise' => $halykScrape->franchise,
                'city' => $halykScrape->city,
                'created_at' => $halykScrape->createdAt,
                'time_end' => $halykScrape->timeEnd,
                'time_start' => $halykScrape->timeStart,
            ];

            foreach($halykScrape->bankType->paymentSystem as $paymentSystem) {
                $scrapePaymentsInsert[] = [
                    'hash' => $halykScrape->hash,
                    'payment_type' => $paymentSystem->value
                ];
            }
        }

        DB::beginTransaction();
        try {
            DB::table('scrapes')->upsert($scrapesInsert, ['hash']);
            DB::table('scrape_payment_types')->upsert($scrapePaymentsInsert, ['hash, payment_type']);
            $scrapeSearch->bulkPush($halykScrapes);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
    }
}

// cashback
// raw
// title
// url
// image_url
// limitation
// condition
// createdAt
// timeEnd
// timeStart
