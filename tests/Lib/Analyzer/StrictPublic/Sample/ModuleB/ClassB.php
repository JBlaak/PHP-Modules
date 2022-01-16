<?php

namespace Sample\ModuleB;

use Sample\ModuleA\ClassA;
use Sample\ModuleA\Internal\InternalClassA;

/**
 * @public
 */
class ClassB
{

    public function run(): void
    {
        $classA = new ClassA();
        $classA->run();

        //This isn't allowed since it is not annotated with @public
        $internalClassA = new InternalClassA();
        $internalClassA->run();
    }
}