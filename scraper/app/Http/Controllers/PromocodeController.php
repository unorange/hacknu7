<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiSearchFullText;
use App\Models\PaymentType;
use App\Models\Scrape;
use App\Scraper\Banks;
use App\Scraper\BankType;
use App\Scraper\ElasticSearch\ScrapeSearch;
use App\Scraper\PaymentSystem;
use App\Scraper\Scrape as ScraperScrape;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PromocodeController extends Controller
{
    public function __construct(
        private ScrapeSearch $scrapeSearch
    ) {
    }

    public function searchFullText(ApiSearchFullText $request): JsonResponse
    {
        $scrapeQuery = Scrape::query()
            ->with(['paymentTypes']);
        $searchResult = $this->scrapeSearch->searchFullText(
            $request->input('query'),
            $request->input('bank'),
            $request->input('category')
        );

        if(!($request->input('force') === 'yes')) {
            $scrapeQuery->where('moderated', true);
        }

        $scrapeModels = $scrapeQuery->whereIn('hash', $searchResult[1])->get();
        $scrapeEntities = [];
        foreach($scrapeModels as $scrapeModel) {
            $scrapeEntities[] = $scrapeModel->toEntity()->toObject();
        }

        return response()->json([
            'hits' => count($scrapeEntities),
            'data' => $scrapeEntities
        ]);
    }

    public function searchDatabaseBank(string $bank, Request $request): JsonResponse
    {
        $scrapeQuery = Scrape::query()
            ->with(['paymentTypes'])
            ->where('bank', $bank);

        if(!($request->input('force') === 'yes')) {
            $scrapeQuery->where('moderated', true);
        }

        /**
         * @var \Illuminate\Database\Eloquent\Collection<Scrape>
         */
        $scrapeModels = $scrapeQuery->get();

        $scrapeEntities = [];
        foreach($scrapeModels as $scrapeModel) {
            $scrapeEntities[] = $scrapeModel->toEntity()->toObject();
        }

        return response()->json($scrapeEntities);
    }
}
