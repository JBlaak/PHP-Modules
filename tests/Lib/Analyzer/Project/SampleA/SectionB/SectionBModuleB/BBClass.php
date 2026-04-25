<?php

namespace Sample\SectionB\SectionBModuleB;

use PhpModules\Attributes\Exposed;
use Sample\SectionA\SectionAModuleB\ABClass;

#[Exposed]
class BBClass
{

    public function get(): ABClass
    {
        return new ABClass();
    }
}