<?php

namespace Sample;

use Sample\ModuleA\ClassA;
use Sample\ModuleC\ClassC;

class ModuleB
{

    public function run(): void
    {
        $classA = new ClassA();
        $classA->run();

        $classC = new ClassC();
        $classC->run();
    }
}