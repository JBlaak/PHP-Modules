<?php

namespace PhpModules\Lib\Errors;

use PhpModules\Lib\Domain\Importable;
use PhpModules\Lib\Module;
use SplFileInfo;

class NotPublicError extends Error
{


    public function __construct(
        public SplFileInfo $file,
        public Importable   $import,
        private Module      $strictModule
    )
    {
        parent::__construct(
            $this->file->getBasename() . ' is not allowed to import `' . $this->import . '` because it is not marked public in strict module `' . $this->strictModule->namespace . '`',
            $this->file->getPathname(),
            $this->import->line
        );
    }
}