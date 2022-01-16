<?php

namespace PhpModules\Lib\Errors;

use PhpModules\Lib\Domain\Importable;

/**
 * @public
 */
abstract class ModuleError
{

    public function __construct(public \SplFileInfo $file, public Importable $import)
    {
    }

    abstract function __toString(): string;
}