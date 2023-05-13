<?php

declare(strict_types=1);

namespace App\Repository\DTO;

class CNBReport
{
    public string $code;

    public float $average;

    public float $minValue;

    public float $maxValue;
}