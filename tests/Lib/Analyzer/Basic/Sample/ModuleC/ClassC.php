<?php

namespace Sample\ModuleC;

use Sample\ModuleA\ClassA as SomeClass;

class ClassC
{

    public function run(): void
    {
        $classA = new SomeClass();
        $classA->run();
    }
}