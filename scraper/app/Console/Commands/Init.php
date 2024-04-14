<?php

namespace App\Console\Commands;

use App\Models\PaymentType;
use App\Scraper\Banks;
use App\Scraper\PaymentSystem;
use Illuminate\Console\Command;

class Init extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $banks = array_map(function (Banks $bankEnum) {
        //     return $bankEnum->value;
        // }, Banks::cases());

        $paymentTypesInsert = array_map(function (PaymentSystem $paymentSystem) {
            return ['name' => $paymentSystem->value];
        }, PaymentSystem::cases());

        PaymentType::insert($paymentTypesInsert);
    }
}
