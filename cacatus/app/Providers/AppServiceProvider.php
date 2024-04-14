<?php

namespace App\Providers;

use App\Scraper\ElasticSearch\ScrapeSearch;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ScrapeSearch::class, function () {
            return new ScrapeSearch(config('app.elastic_url'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
