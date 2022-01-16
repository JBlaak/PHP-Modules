<?php

namespace PhpModules\Lib\Domain;

/**
 * Details of a class
 */
class ClassDefinition
{

    public function __construct(public ClassName $className, public ?string $phpdoc)
    {
    }
}