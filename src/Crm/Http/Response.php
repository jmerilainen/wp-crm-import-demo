<?php

namespace Jmerilainen\WpDemoCrm\Crm\Http;

use ArrayIterator;
use Countable;
use IteratorAggregate;

class Response implements IteratorAggregate, Countable
{
    protected $response;

    protected $data;

    public function __construct($response)
    {
        $this->response = $response;

        $this->data = json_decode($this->body());
    }

    public function __get($name)
    {
        return $this->data->$name;
    }

    public function status()
    {
        return wp_remote_retrieve_response_code($this->response);
    }

    public function hasErrors()
    {
        return $this->isWpError() || isset($this->contents()['errors'][0]) || $this->statusCode() != 200;
    }

    public function isWpError()
    {
        return is_wp_error($this->response);
    }

    public function errors()
    {
        if ($this->isWpError()) {
            return (object) [
                'status' => 0,
                'detail' => $this->response->get_error_message(),
            ];
        }

        if (isset($this->contents()['errors'][0])) {
            return (object) $this->contents()['errors'][0];
        }

        if ($this->response['response']) {
            return (object) [
                'status' => $this->response['response']['code'],
                'detail' => $this->response['response']['message'] . ' ' . $this->response['response']['code'],
            ];
        }

        return (object) [
            'status' => 0,
            'detail' => 'Unknown error',
        ];
    }

    public function body()
    {
        return wp_remote_retrieve_body($this->response);
    }

    public function statusCode()
    {
        return wp_remote_retrieve_response_code($this->response);
    }

    public function contents()
    {
        return json_decode($this->body(), true);
    }

    public function data()
    {
        return $this->contents()['data'] ?? [];
    }

    public function links()
    {
        return $this->contents()['links'] ?? [];
    }

    public function getIterator()
    {
        $data = $this->data->data ?? [];

        return new ArrayIterator($data);
    }

    public function count()
    {
        return count($this->data->data ?? []);
    }
}
