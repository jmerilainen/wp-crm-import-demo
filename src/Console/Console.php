<?php

namespace Jmerilainen\WpDemoCrm\Console;

use WP_CLI;

class Console
{
    protected $commands = [
        Commands\AccountsCommand::class,
    ];

    public function register()
    {
        add_action('cli_init', [$this, 'register_commands']);
    }

    public function register_commands()
    {
        foreach($this->commands as $command) {
            $instance = new $command;

            WP_CLI::add_command($instance->command(), $instance);
        }
    }
}
