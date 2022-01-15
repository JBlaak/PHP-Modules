<?php

namespace PhpModules;

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