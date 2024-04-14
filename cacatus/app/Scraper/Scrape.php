<?php

declare(strict_types=1);

namespace App\Scraper;

class Scrape
{
    /**
     * @param string $hash
     * @param \App\Scraper\BankType $bankType
     * @param string $raw
     * @param string $title
     * @param string $url
     * @param null|int $cashback
     * @param null|string $image_url
     * @param null|string $limitation
     * @param null|string $condition
     * @param null|string $category
     * @param null|string $franchise
     * @param null|string $city
     * @param null|int $createdAt
     * @param null|int $timeEnd
     * @param null|int $timeStart
     */
    public function __construct(
        public readonly string $hash,
        public readonly BankType $bankType,
        public readonly string $raw,
        public readonly string $title,
        public readonly string $url,
        public readonly ?int $cashback = null,
        public readonly ?string $image_url = null,
        public readonly ?string $limitation = null,
        public readonly ?string $condition = null,
        public readonly ?string $category = null,
        public readonly ?string $franchise = null,
        public readonly ?string $city = null,
        public readonly ?int $createdAt = null,
        public readonly ?int $timeEnd = null,
        public readonly ?int $timeStart = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'hash' => $this->hash,
            'bank_type' => $this->bankType->toArray(),
            'cashback' => $this->cashback,
            'raw' => $this->raw,
            'title' => $this->title,
            'url' => $this->url,
            'image_url' => $this->image_url,
            'limitation' => $this->limitation,
            'condition' => $this->condition,
            'category' => $this->category,
            'franchise' => $this->franchise,
            'city' => $this->city,
            'created_at' => $this->createdAt,
            'time_end' => $this->timeEnd,
            'time_start' => $this->timeStart,
        ];
    }

    public function toObject(): object
    {
        return (object)[
            'hash' => $this->hash,
            'bank_type' => $this->bankType->toObject(),
            'cashback' => $this->cashback,
            'raw' => $this->raw,
            'title' => $this->title,
            'url' => $this->url,
            'image_url' => $this->image_url,
            'limitation' => $this->limitation,
            'condition' => $this->condition,
            'category' => $this->category,
            'franchise' => $this->franchise,
            'city' => $this->city,
            'created_at' => $this->createdAt,
            'time_end' => $this->timeEnd,
            'time_start' => $this->timeStart,
        ];
    }
}
