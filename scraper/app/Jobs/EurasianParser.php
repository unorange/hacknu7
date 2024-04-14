<?php

namespace App\Jobs;

use App\Scraper\ElasticSearch\ScrapeSearch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Scraper\Internal\Eurasion\Master;
use App\Scraper\Internal\Eurasion\Parser;
use Illuminate\Support\Facades\Log;

class EurasianParser implements ShouldQueue
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
        $euroScrapes = (new Parser((new Master())->getLinks()))->scrape();
        $scrapesInsert = [];
        $scrapePaymentsInsert = [];
        foreach($euroScrapes as $euroScrape) {
            Log::info(gettype($euroScrape));
            $scrapesInsert[] = [
                'hash' => $euroScrape->hash,
                'bank' => $euroScrape->bankType->bank->value,
                'cashback' => $euroScrape->cashback,
                'raw' => $euroScrape->raw,
                'title' => $euroScrape->title,
                'url' => $euroScrape->url,
                'image_url' => $euroScrape->image_url,
                'limitation' => $euroScrape->limitation,
                'condition' => $euroScrape->condition,
                'category' => $euroScrape->category,
                'franchise' => $euroScrape->franchise,
                'city' => $euroScrape->city,
                'created_at' => $euroScrape->createdAt,
                'time_end' => $euroScrape->timeEnd,
                'time_start' => $euroScrape->timeStart,
            ];

            foreach($euroScrape->bankType->paymentSystem as $paymentSystem) {
                $scrapePaymentsInsert[] = [
                    'hash' => $euroScrape->hash,
                    'payment_type' => $paymentSystem->value
                ];
            }
        }

        DB::beginTransaction();
        try {
            DB::table('scrapes')->upsert($scrapesInsert, ['hash']);
            DB::table('scrape_payment_types')->upsert($scrapePaymentsInsert, ['hash, payment_type']);
            $scrapeSearch->bulkPush($euroScrapes);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
    }
}
