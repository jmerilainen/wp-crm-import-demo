<?php

namespace Jmerilainen\WpDemoCrm;

class Plugin
{
    protected $services = [
        Console\Console::class,
    ];

    protected $booted;

    public function activate() {}

    public function deactivate() {}

    public function bootstrap()
    {
        if ($this->booted) {
            return;
        }

        $this->register();

        $this->booted = true;
    }

    public function register()
    {
        foreach($this->services as $service) {
            (new $service)->register();
        }
    }
}
