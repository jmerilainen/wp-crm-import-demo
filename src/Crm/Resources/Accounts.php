<?php

namespace Jmerilainen\WpDemoCrm\Crm\Resources;

class Accounts extends Resource
{
    public function all(array $query = [])
    {
        $endpoint = 'api/v1/accounts';

        return $this->client
            ->endpoint($endpoint)
            ->query($query)
            ->get();
    }
}
