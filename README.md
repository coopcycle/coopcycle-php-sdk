CoopCycle PHP SDK
=================

Installation
------------

The recommended way to install **coopcycle-php-sdk** is through [composer](http://getcomposer.org).

Run the following on the command line:

```
php composer require coopcycle/coopcycle-php-sdk
```

Usage
-----

To instantiate the client, you will need a client id and a client secret.

```php
use CoopCycle\Client;

$client = new Client([
    'base_uri'      => 'https://acme.coopcycle.org',
    'client_id'     => 'YourClientId',
    'client_secret' => 'YourClientSecret'
]);
```

### Creating deliveries

You can use the client to create deliveries.

```php
$request = [
    'pickup' => [
        'address' => '50, Rue de Rivoli, Paris'
    ],
    'dropoff' => [
        'address' => '90, Rue de Rivoli, Paris',
        'before' => 'today 13:00'
    ],
];

$response = $client->deliveries->create($request);
```

### Creating webhooks

You can also use the client to subscribe to webhooks.

```php
$data = [
    'event' => 'delivery.completed',
    'url' => 'https://coopcycle.org/webhook',
];

$response = $client->webhooks->create($data);
```

### Verifying webhooks signature

When you create a webhook, the response object will contain a `secret` property.
Make sure to store this `secret` somewhere on your system, to later verify the signature.

```json
{
    "@context":"/api/contexts/Webhook",
    "@id":"/api/webhooks/1",
    "@type":"Webhook",
    "url":"https://example.com/webhook",
    "event":"delivery.completed",
    "secret":"4mCOyJ7UAa371oUjYcC2R9BZRx5eQT08qTzLAnh4e8M="
}
```

When you receive a webhook on your endpoint, the `X-CoopCycle-Signature` header will contain a HMAC signature.

Here is an example Symfony controller:

```php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WebhookHandler {

    public function __invoke(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->headers->get('X-CoopCycle-Signature');

        // You will probably restore the secret from your database instead
        $secret = "4mCOyJ7UAa371oUjYcC2R9BZRx5eQT08qTzLAnh4e8M=";

        if (!$client->webhooks->verifySignature($payload, $signature, $secret)) {
            return new Response('Invalid webhook signature', 400);
        }

        return new Response('OK', 200);
    }
}
```
