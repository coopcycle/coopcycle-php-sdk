<?php

namespace CoopCycle;

use GuzzleHttp\Client;

class Webhooks
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function create(array $payload)
    {
        $response = $this->client->post('/api/webhooks', [
            'headers' => [
                'Content-Type' => 'application/ld+json'
            ],
            'json' => $payload
        ]);

        return json_decode((string) $response->getBody(), true);
    }
}
