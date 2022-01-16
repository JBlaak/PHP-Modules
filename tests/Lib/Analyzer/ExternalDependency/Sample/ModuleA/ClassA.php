<?php

namespace Sample\ModuleA;

use Graphp\GraphViz\GraphViz;

class ClassA
{

    public function run(): void
    {
        //Just by "chance" we have this dependency in the project
        $graphViz = new GraphViz();
        echo "Hi!";
    }
}