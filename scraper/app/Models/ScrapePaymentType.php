<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scrape;
use App\Models\PaymentType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScrapePaymentType extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'payment_type',
        'hash'
    ];

    public function scrape(): BelongsTo
    {
        return $this->belongsTo(Scrape::class, "hash", "hash");
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class, "payment_types", "name");
    }
}
