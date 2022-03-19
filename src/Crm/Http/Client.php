<?php

namespace Jmerilainen\WpDemoCrm\Crm\Http;

use Exception;
use Jmerilainen\WpDemoCrm\Crm\Exception\InvalidApiUser;

class Client
{
    use Request;

    protected $baseUri;

    protected $token;

    public function __construct($config = [])
    {
        $this->baseUri = getenv('CRM_API_BASE_URI');

        foreach($config as $key => $value) {
            $this->$key = $value;
        }
    }

    public function request($method)
    {
        $url = $this->url($this->endpoint, $this->query);

        $options = $this->options;

        $options['body'] = $this->body;
        $options['method'] = $method;
        $options['headers']['Content-Type'] = 'application/json';

        if ($this->token) {
            $options['headers']['Authorization'] = "Bearer {$this->token}";
        }

        if (! empty($options['body'])) {
            $options['body'] = wp_json_encode($options['body']);
        }

        $response = new Response(wp_safe_remote_request($url, $options));

        if ($response->hasErrors()) {
            if ($response->errors()->status == 401) {
                throw new InvalidApiUser($response->errors()->detail);
            }

            throw new Exception($response->errors()->detail . ' ' . $url);
        }

        return $response;
    }

    protected function url($endpoint, array $query = [])
    {
        $url = sprintf('%s/%s', rtrim($this->baseUri, '/'), $endpoint);

        return add_query_arg($query, $url);
    }

    public function token()
    {
        return $this->token;
    }

    public function get()
    {
        return $this->request('GET');
    }

    public function post()
    {
        return $this->request('POST');
    }
}
