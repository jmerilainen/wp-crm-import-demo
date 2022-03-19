<?php

namespace Jmerilainen\WpDemoCrm\Models;

use Exception;

abstract class Model
{
    protected $post_type;

    protected $attributes = [];

    public $exists = false;

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    protected function updateOrCreate(array $attributes, array $values = [])
    {
        $defaults = [
            'posts_per_page' => 1,
            'post_type' => $this->post_type,
            'post_status' => 'any',
        ];

        $args = wp_parse_args($attributes, $defaults);
        $query = get_posts($args);

        $instance = new static($values);

        if (isset($query[0]->ID)) {
            $newData = array_merge(
                (array) $query[0],
                $values
            );
            $instance = new static($newData);
            $instance->exists = true;
        }

        $instance->save();

        return $instance;
    }

    public function save()
    {
        $data = $this->formatToSave();

        if (! $data) {
            return;
        }

        $data['post_type'] = $this->post_type;

        if ($this->exists) {
            if (! $this->shouldUpdate()) {
                return true;
            }

            $data = array_merge($this->attributes, $data);
            unset($data['post_modified']);
            unset($data['post_modified_gmt']);
            $id = wp_update_post($data, true);
        } else {
            $id = wp_insert_post($data, true);
        }

        if (is_wp_error($id)) {
            throw new Exception($id->get_error_message());
        }

        $terms = $data['tax_input'] ?? [];

        foreach($terms as $taxonomy => $items) {
            wp_set_object_terms($id, $items, $taxonomy);
        }

        $acf_inputs = $data['acf_input'] ?? [];

        foreach($acf_inputs as $key => $value) {
            update_field($key, $value, $id);
        }

        $this->ID = $id;

        $this->exists = true;

        return true;
    }

    abstract protected function formatToSave();

    protected function shouldUpdate()
    {
        return true;
    }

    public function __get($name)
    {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }

        return $this->$name ?? '';
    }

    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }
}
