<?php

namespace PhpModules\Lib\Errors;

use PhpModules\Lib\Domain\Importable;

class Undefined extends Error
{

    public function __construct(
        public \SplFileInfo $file,
        public Importable   $import,
    )
    {
        parent::__construct(
            $this->file->getBasename() . ' is not allowed to import `' . $this->import . '` it isn\'t defined as a module.',
            $this->file->getPathname(),
            $this->import->line
        );
    }

}