<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class PaymentType extends Model
{
    protected $primaryKey = 'name';
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = ['name'];

    public function scrapes(): HasManyThrough
    {
        return $this->hasManyThrough(
            Scrape::class,
            ScrapePaymentType::class,
            'payment_type',
            'hash',
            'name',
            'hash'
        );
    }

}
