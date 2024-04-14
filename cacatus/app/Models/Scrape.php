<?php

namespace App\Models;

use App\Scraper\Banks;
use App\Scraper\BankType;
use App\Scraper\PaymentSystem;
use App\Scraper\Scrape as ScraperScrape;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Scrape extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'hash';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'hash',
        'bank',
        'cashback',
        'raw',
        'title',
        'url',
        'image_url',
        'limitation',
        'condition',
        'category',
        'franchise',
        'city',
        'created_at',
        'time_end',
        'time_start',
        'moderated'
    ];

    public function paymentTypes(): HasManyThrough
    {
        return $this->hasManyThrough(
            PaymentType::class,
            ScrapePaymentType::class,
            'hash',
            'name',
            'hash',
            'payment_type'
        );
    }

    public function toEntity(): ScraperScrape
    {
        return new ScraperScrape(
            hash: $this->hash,
            bankType: new BankType(
                Banks::from($this->bank),
                $this->paymentTypes->map(function (PaymentType $paymentType) {
                    return PaymentSystem::from($paymentType->name);
                })->all()
            ),
            cashback: $this->cashback,
            raw: $this->raw,
            title: $this->title,
            url: $this->url,
            image_url: $this->image_url,
            limitation: $this->limitation,
            condition: $this->condition,
            category: $this->category,
            franchise: $this->franchise,
            city: $this->city,
            createdAt: Carbon::parse($this->created_at)->timestamp,
            timeEnd: Carbon::parse($this->time_end)->timestamp,
            timeStart: Carbon::parse($this->time_start)->timestamp,
        );
    }
}
