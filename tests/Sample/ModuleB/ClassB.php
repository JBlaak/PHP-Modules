<?php

namespace Sample\ModuleB;

use Sample\ModuleA\ClassA;

class ClassB
{

    public function run()
    {
        $classA = new ClassA();
        $classA->run();
    }
}