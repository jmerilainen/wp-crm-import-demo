<?php

namespace Jmerilainen\WpDemoCrm\Console\Commands;

use WP_CLI;

abstract class Command
{
    protected $command;

    public function command()
    {
        return $this->command;
    }

    protected function line($output)
    {
        return WP_CLI::line($output);
    }

    protected function debug($message, $group = false)
    {
        return WP_CLI::debug($message, $this->command);
    }

    protected function progress($message, $count)
    {
        return \WP_CLI\Utils\make_progress_bar($message, $count);
    }
}
