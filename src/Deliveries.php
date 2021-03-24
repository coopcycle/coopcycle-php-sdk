<?php

namespace CoopCycle;

use GuzzleHttp\Client;

class Deliveries
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function create(array $payload)
    {
        $response = $this->client->post('/api/deliveries', [
            'headers' => [
                'Content-Type' => 'application/ld+json'
            ],
            'json' => $payload
        ]);

        return json_decode((string) $response->getBody(), true);
    }
}
