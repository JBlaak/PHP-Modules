<?php

namespace PhpModules\Lib\Errors;

use PhpModules\Lib\Module;

class UnusedDependency extends Error
{

    public function __construct(
        private Module      $module,
        private Module      $dependency,
    )
    {
        parent::__construct(
            $this->module->namespace . ' is depending on ' . $this->dependency->namespace . ' but it is not used.',
            'modules.php',
            null,
        );
    }
}