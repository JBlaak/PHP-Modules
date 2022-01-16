<?php

namespace Sample\ModuleA;

use Sample\ModuleA\Internal\InternalClassA;

/**
 * @public
 */
class ClassA
{

    public function run(): void
    {
        $internalClassA = new InternalClassA();
        $internalClassA->run();
    }
}