<?php

namespace PhpModules\Lib;

class Result
{

    /**
     * @var DependencyError[]
     */
    public array $errors;

    /**
     * @param DependencyError[] $errors
     */
    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }


}