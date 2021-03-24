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

```php
require 'vendor/autoload.php';

use CoopCycle\Client;

$client = new Client([
    'base_uri'      => 'https://acme.coopcycle.org',
    'client_id'     => 'YourClientId',
    'client_secret' => 'YourClientSecret'
]);

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
