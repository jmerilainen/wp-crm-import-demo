<?php

namespace Jmerilainen\WpDemoCrm\Repositories;

use WP_Query;

class AccountRepository
{
    protected $post_type = 'account';

    public function purge(array $keepIds = []): array
    {
        $query = new WP_Query([
            'post_type' => $this->post_type,
            'post__not_in' => $keepIds,
            'posts_per_page' => 500,
            'post_status' => ['publish', 'draft'],
            'fields' => 'ids',
        ]);

        foreach ($query->posts as $id) {
            wp_delete_post($id);
        }

        return $query->posts;
    }
}
