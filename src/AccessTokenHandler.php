<?php

namespace CoopCycle;

use GuzzleHttp\Client;
use Psr\Http\Message\RequestInterface;

class AccessTokenHandler
{
    private $client;
    private $accessToken;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options) use ($handler) {

            if (!$request->hasHeader('Authorization')) {
                $accessToken = $this->getAccessToken();
                $request = $request->withHeader('Authorization', sprintf('Bearer %s', $accessToken));
            }

            return $handler($request, $options);
        };
    }

    private function getAccessToken()
    {
        if (null === $this->accessToken) {

            $base64Credentials = base64_encode(sprintf('%s:%s',
                $this->client->getConfig('client_id'),
                $this->client->getConfig('client_secret')));

            $response = $this->client->post('/oauth2/token', [
                'headers' => [
                    'Authorization' => sprintf('Basic %s', $base64Credentials)
                ],
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'scope' => 'tasks deliveries'
                ]
            ]);

            $data = json_decode((string) $response->getBody(), true);

            $this->accessToken = $data['access_token'];
        }

        return $this->accessToken;
    }
}
