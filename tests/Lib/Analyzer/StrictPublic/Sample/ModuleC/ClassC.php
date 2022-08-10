<?php

namespace Sample\ModuleC;

use Sample\ModuleC\Internal\InternalClassC;

/**
 * @public
 */
class ClassC
{

    public function run(): void
    {
        $internalClassA = new InternalClassC();
        $internalClassA->run();
    }
}