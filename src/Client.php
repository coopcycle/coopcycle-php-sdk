<?php

namespace CoopCycle;

use GuzzleHttp\Client as BaseClient;
use GuzzleHttp\HandlerStack;

class Client extends BaseClient
{
    public $deliveries;

    public function __construct(array $config = [])
    {
        $stack = HandlerStack::create();
        $stack->push(new AccessTokenHandler($this));

        $config['handler'] = $stack;

        parent::__construct($config);

        $this->deliveries = new Deliveries($this);
    }
}
