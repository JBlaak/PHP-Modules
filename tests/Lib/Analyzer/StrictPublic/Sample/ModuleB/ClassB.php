<?php

namespace Sample\ModuleB;

use PhpModules\Attributes\Exposed;
use Sample\ModuleA\ClassA;
use Sample\ModuleA\Internal\InternalClassA;

#[Exposed]
class ClassB
{

    public function run(): void
    {
        $classA = new ClassA();
        $classA->run();

        //This isn't allowed since it is not annotated with #[Exposed]
        $internalClassA = new InternalClassA();
        $internalClassA->run();
    }
}