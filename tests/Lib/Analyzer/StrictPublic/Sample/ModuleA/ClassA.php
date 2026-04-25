<?php

namespace Sample\ModuleA;

use PhpModules\Attributes\Exposed;
use Sample\ModuleA\Internal\InternalClassA;

#[Exposed]
class ClassA
{

    public function run(): void
    {
        $internalClassA = new InternalClassA();
        $internalClassA->run();
    }
}