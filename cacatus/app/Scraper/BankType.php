<?php

declare(strict_types=1);

namespace App\Scraper;

class BankType
{
    /**
     * @param \App\Scraper\Banks $bank
     * @param \App\Scraper\PaymentSystem[] $paymentSystem
     * @param null|string $cardType
     */
    public function __construct(
        public readonly Banks $bank,
        public readonly array $paymentSystem,
        public readonly ?string $cardType = null
    ) {
    }

    public function toArray(): array
    {
        return [
            'bank' => $this->bank->value,
            'payment_systems' => array_map(function (PaymentSystem $paymentSystem) {
                return $paymentSystem->value;
            }, $this->paymentSystem),
            'card_type' => $this->cardType
        ];
    }

    public function toObject(): object
    {
        return (object) $this->toArray();
    }
}
