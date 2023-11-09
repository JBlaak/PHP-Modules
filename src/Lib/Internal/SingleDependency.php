<?php

namespace PhpModules\Lib\Internal;

use PhpModules\Lib\Module;

class SingleDependency
{

    public bool $isUsed = false;

    public function __construct(public Module $module, public Module $dependency)
    {
    }

    public function __toString()
    {
        return $this->module->namespace . ' -> ' . $this->dependency->namespace;
    }
}