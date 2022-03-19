<?php

namespace Jmerilainen\WpDemoCrm\Crm;

use Jmerilainen\WpDemoCrm\Crm\Http\Client;
use Jmerilainen\WpDemoCrm\Crm\Exception\InvalidResource;

class Factory
{
    protected $client;

    public function __construct($config = [])
    {
        $this->client = new Client($config);
    }

    public function __call($name, $args)
    {
        $resource = __NAMESPACE__ .'\\Resources\\'.ucfirst($name);

        if (! class_exists($resource)) {
            throw new InvalidResource("Resource \"$name\" has not been implemented.");
        }

        return new $resource($this->client);
    }

    public static function make()
    {
        return new static;
    }

    public static function makeWithToken()
    {
        $token = (new static)->auth()->token(
            getenv('CRM_API_USER'),
            getenv('CRM_API_PASSWORD')
        );

        return new static([
            'token' => $token->contents(),
        ]);
    }

    public function token()
    {
        return $this->client->token();
    }
}
