<?php

namespace CoopCycle\Tests;

use CoopCycle\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class WebhooksTest extends TestCase
{
    private function createSignature($payload, $secret)
    {
        if (!is_string($payload)) {
            $payload = json_encode($payload);
        }

        $hexHash = hash_hmac('sha256', $payload, $secret);

        return base64_encode(hex2bin($hexHash));
    }

    public function testVerifySignature()
    {
        $secret = '0123456789';
        $otherSecret = '987654321';

        $payload = [
            'data' => [
                'object' => '/api/deliveries/1',
                'event' => 'delivery.completed',
            ]
        ];

        $signature = $this->createSignature($payload, $secret);

        $client = new Client([
            'base_uri' => 'https://demo.coopcycle.org',
            'client_id' => 'abc0123456',
            'client_secret' => 'def654321'
        ]);

        $this->assertTrue($client->webhooks->verifySignature(json_encode($payload), $signature, $secret));
        $this->assertFalse($client->webhooks->verifySignature(json_encode($payload), $signature, $otherSecret));
    }
}
