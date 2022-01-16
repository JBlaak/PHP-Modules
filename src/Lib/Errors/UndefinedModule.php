<?php

namespace PhpModules\Lib\Errors;

use PhpModules\Lib\Domain\Importable;

class UndefinedModule extends ModuleError
{

    public function __construct(
        public \SplFileInfo $file,
        public Importable   $import,
    )
    {
        parent::__construct($this->file, $this->import);
    }

    public function __toString(): string
    {
        return $this->file->getBasename() . ' is not allowed to import `' . $this->import . '` it isn\'t defined as a module.';
    }

}