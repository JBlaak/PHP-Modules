<?php

namespace PhpModules\Lib\Domain;

/**
 * Details of a class
 */
class ClassDefinition
{
    public bool $isEnum;

    public function __construct(public ClassName $className, public bool $isExposed, bool $isEnum = false)
    {
        $this->isEnum = $isEnum;
    }
}
