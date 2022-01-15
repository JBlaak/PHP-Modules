<?php

namespace PhpModules;

class DependencyError
{

    public function __construct(public \SplFileInfo $file, public NamespaceName $dependency)
    {
    }
}