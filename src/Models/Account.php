<?php

namespace Jmerilainen\WpDemoCrm\Models;

class Account extends Model
{
    protected $post_type = 'account';

    public function formatToSave()
    {
        return [
            'post_status' => $this->active ? 'publish' : 'draft',
            'post_title' => $this->name,
            'post_content' => $this->description,
            'acf_input' => $this->formatToAcf(),
            'meta_input' => [
                'payload' => $this->payload,
                'checksum' => $this->checksum(),
            ],
            'tax_input' => [
                'type' => $this->type,
            ],
        ];
    }

    protected function checksum()
    {
        return md5(serialize($this->payload));
    }

    protected function shouldUpdate()
    {
        return $this->checksum() !== get_post_meta($this->ID, 'checksum', true);
    }

    protected function formatToAcf()
    {
        return [
            'account_id' => $this->account_id,
            'email' => $this->email,
            'city' => $this->city,
            'zip' => $this->zip,
        ];
    }
}
