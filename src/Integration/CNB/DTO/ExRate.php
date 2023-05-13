<?php

declare(strict_types=1);

namespace App\Integration\CNB\DTO;

class ExRate
{
    public string $validFor;
    public int $order;
    public string $country;
    public string $currency;
    public int $amount;
    public string $currencyCode;
    public float $rate;
}