<?php

class PHPengine
{
    private $log;
    protected $dir;

    public function __construct($settings)
    {
        if (isset($settings['datadir'])) $this->dir = ROOT.$settings['datadir'].DS;
        $this->log = new EmonLogger(__FILE__);
    }
}

