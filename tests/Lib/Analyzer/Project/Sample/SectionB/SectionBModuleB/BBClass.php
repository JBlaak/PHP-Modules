<?php

namespace Sample\SectionB\SectionBModuleB;

use Sample\SectionA\SectionAModuleB\ABClass;

/**
 * @public
 */
class BBClass
{

    public function get(): ABClass
    {
        return new ABClass();
    }
}