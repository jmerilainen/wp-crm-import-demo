<?php

namespace Jmerilainen\WpDemoCrm\Transformers;

class AccountToPost
{
    public function transform($response)
    {
        return [
            'account_id' => $response->id,
            'name' => $response->attributes->name,
            'email' => $response->attributes->email,
            'city' => $response->attributes->city,
            'zip' => $response->attributes->zip,
            'type' => $this->mapType($response->attributes->type),
            'active' => $this->isActive($response),
            'description' => $response->attributes->description,
            'payload' => $response,
        ];
    }

    protected function isActive($response)
    {
        return $response->attributes->status === 'active'
            && $response->attributes->name;
    }

    protected function mapType($type)
    {
        $map = [
            '3462' => 'Global',
            '2474' => 'Local',
        ];

        return $map[$type] ?? '';
    }
}
