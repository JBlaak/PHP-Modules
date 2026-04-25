<?php

namespace Sample\SectionB\SectionBModuleB;

use PhpModules\Attributes\Exposed;
use Sample\SectionA\SectionAModuleA\AAClass;

#[Exposed]
class BBClass
{

    public function get(): AAClass
    {
        return new AAClass();
    }
}