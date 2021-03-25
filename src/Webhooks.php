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

    /**
     * @var string $payload
     * @var string $signature
     * @var string $secret
     *
     * @return bool
     */
    public function verifySignature($payload, $signature, $secret)
    {
        // https://resthooks.org/docs/security/
        // https://www.docusign.com/blog/developers/hmac-verification-php
        $hexHash = hash_hmac('sha256', $payload, $secret);

        return $signature === base64_encode(hex2bin($hexHash));
    }
}
