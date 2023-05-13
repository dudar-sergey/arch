<?php

declare(strict_types=1);

namespace App\Integration\CNB;

use App\Integration\CNB\DTO\CNBDailyResponse;
use App\Integration\CNB\DTO\ExRate;
use DateTime;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HttpClient
{
    public function __construct(private HttpClientInterface $client, private string $host, private SerializerInterface $serializer)
    {
        $this->client = $this->client->withOptions(
            [
                'base_uri' => $this->host
            ]
        );
    }

    /**
     * @param DateTime $date
     */
    public function getRateByDate(DateTime $date): CNBDailyResponse
    {
        $options = [
            'query' => [
                'date' => $date->format('Y-m-d'),
                'lang' => 'EN'
            ]
        ];

        $response = $this->client->request('GET', '/cnbapi/exrates/daily', $options);


        return $this->serializer->deserialize($response->getContent(), CNBDailyResponse::class, 'json');
    }

}