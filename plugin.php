<?php

namespace Jmerilainen\WpDemoCrm;

/*
Plugin Name:  Demo CRM integration
Plugin URI:   https://demo.fi/
Description:  Demo
Version:      1.0
Author:       jmerilainen
Author URI:   https://jmerilainen.fi/
*/

if ( ! defined( 'WPINC' ) ) {
    die;
}

require __DIR__ . '/autoload.php';

$plugin = __NAMESPACE__ . '\\Plugin';

register_activation_hook(__FILE__, [new $plugin, 'activate']);

register_deactivation_hook(__FILE__, [new $plugin, 'deactivate']);

add_action('plugins_loaded', [new $plugin, 'bootstrap']);
