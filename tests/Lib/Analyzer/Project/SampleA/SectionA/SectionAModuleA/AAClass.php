<?php

namespace Sample\SectionA\SectionAModuleA;

use Sample\SectionA\SectionAModuleB\ABClass;

/**
 * @public
 */
class AAClass
{

    public function get(): ABClass
    {
        return new ABClass();
    }

}