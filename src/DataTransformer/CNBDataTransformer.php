<?php

declare(strict_types=1);

namespace App\DataTransformer;

use App\Entity\Rate;
use App\Integration\CNB\DTO\CNBDailyResponse;

class CNBDataTransformer
{
    /**
     * @return Rate[]
     */
    public function transform(CNBDailyResponse $response): array
    {
        $result = [];

        foreach ($response->rates as $rate) {
            $entity = new Rate();
            $entity->setRate($rate->rate);
            $entity->setCode($rate->currencyCode);
            $entity->setAmount($rate->amount);
            $entity->setDate(\DateTime::createFromFormat('Y-m-d', $rate->validFor));
            $entity->setCurrency($rate->currency);
            $entity->setCountry($rate->country);

            $result[] = $entity;
        }

        return $result;
    }
}