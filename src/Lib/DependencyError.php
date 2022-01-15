<?php

namespace PhpModules\Lib;

use PhpModules\Lib\Internal\NamespaceName;

class DependencyError
{

    public function __construct(public \SplFileInfo $file, public NamespaceName $dependency)
    {
    }
}