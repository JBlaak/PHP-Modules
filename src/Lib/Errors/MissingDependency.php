<?php

namespace PhpModules\Lib\Errors;

use PhpModules\Lib\Domain\Importable;
use PhpModules\Lib\Module;
use SplFileInfo;

class MissingDependency extends Error
{

    public function __construct(
        public SplFileInfo $file,
        public Importable   $import,
        private Module      $module,
        private Module      $dependency
    )
    {
        parent::__construct(
            $this->file->getBasename() . ' is not allowed to import `' . $this->import . '` because `' . $this->module->namespace . '` doesnt depend on `' . $this->dependency->namespace . '`',
            $this->file->getPathname(),
            $this->import->line
        );
    }

}