<?php

namespace Jmerilainen\WpDemoCrm\Crm\Resources;

abstract class Resource
{
    public function __construct($client)
    {
        $this->client = $client;
    }
}
