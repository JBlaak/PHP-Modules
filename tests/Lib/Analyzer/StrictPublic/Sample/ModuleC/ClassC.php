<?php

namespace Sample\ModuleC;

use PhpModules\Attributes\Exposed;
use Sample\ModuleC\Internal\InternalClassC;

#[Exposed]
class ClassC
{

    public function run(): void
    {
        $internalClassA = new InternalClassC();
        $internalClassA->run();
    }
}