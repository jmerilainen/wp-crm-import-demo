<?php

namespace Jmerilainen\WpDemoCrm\Crm\Resources;

class Auth extends Resource
{
    public function token($username, $password)
    {
        $endpoint = 'api/v1/auth';

        $body = [
            'username' => $username,
            'password' => $password,
        ];

        return $this->client
            ->endpoint($endpoint)
            ->body($body)
            ->post();
    }
}
