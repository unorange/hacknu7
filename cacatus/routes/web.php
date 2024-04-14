<?php

use App\Scraper\Internal\Eurasion\Master;
use App\Scraper\Internal\Eurasion\Parser;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Route;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Promise\Utils;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get("/euro", function () {
    $m = new Master();
    $p = new Parser($m->getLinks());
    dd($p->scrape());
});
