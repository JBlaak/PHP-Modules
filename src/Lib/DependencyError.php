<?php

namespace PhpModules\Lib;

use PhpModules\Lib\Domain\Importable;

/**
 * @public
 */
class DependencyError
{

    public function __construct(public \SplFileInfo $file, public Importable $import)
    {
    }
}