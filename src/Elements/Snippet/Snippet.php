<?php

namespace MatDave\MODXPackage\Elements\Snippet;

abstract class Snippet
{
    protected $modx;

    protected $service;

    protected $scriptProperties = [];

    public function __construct(&$service, array $scriptProperties)
    {
        $this->service =& $service;
        $this->modx =& $this->service->modx;
        $this->scriptProperties = $scriptProperties;
    }

    abstract public function run();

    protected function getOption($key, $default = null, $skipEmpty = false)
    {
        return $this->modx->getOption($key, $this->scriptProperties, $default, $skipEmpty);
    }
}
