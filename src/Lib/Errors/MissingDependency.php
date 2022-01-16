<?php

namespace PhpModules\Lib\Errors;

use PhpModules\Lib\Domain\Importable;
use PhpModules\Lib\Module;

class MissingDependency extends ModuleError
{

    public function __construct(
        public \SplFileInfo $file,
        public Importable   $import,
        private Module      $module,
        private Module      $dependency
    )
    {
        parent::__construct($this->file, $this->import);
    }

    public function __toString(): string
    {
        return $this->file->getBasename() . ' is not allowed to import `' . $this->import . '` because `' . $this->module->namespace . '` doesnt depend on `' . $this->dependency->namespace . '`';
    }

}