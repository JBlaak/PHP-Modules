<?php

namespace Sample\SectionA\SectionAModuleA;

use PhpModules\Attributes\Exposed;
use Sample\SectionA\SectionAModuleB\ABClass;

#[Exposed]
class AAClass
{

    public function get(): ABClass
    {
        return new ABClass();
    }

}
