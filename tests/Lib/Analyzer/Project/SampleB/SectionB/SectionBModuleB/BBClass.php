<?php

namespace Sample\SectionB\SectionBModuleB;

use Sample\SectionA\SectionAModuleA\AAClass;

/**
 * @public
 */
class BBClass
{

    public function get(): AAClass
    {
        return new AAClass();
    }
}