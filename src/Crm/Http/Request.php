<?php

namespace Jmerilainen\WpDemoCrm\Crm\Http;

trait Request
{
    protected $client;

    protected $endpoint;

    protected $query = [];

    protected $body;

    protected $options;

    public function endpoint(string $endpoint)
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function query(array $query = [])
    {
        $this->query = $query;

        return $this;
    }

    public function body($body)
    {
        $this->body = $body;

        return $this;
    }

    public function options($options)
    {
        $this->options = $options;

        return $this;
    }
}
