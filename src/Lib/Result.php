<?php

namespace PhpModules\Lib;

use PhpModules\Lib\Errors\ModuleError;

/**
 * @public
 */
class Result
{

    /**
     * @var ModuleError[]
     */
    public array $errors;

    /**
     * @param ModuleError[] $errors
     */
    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }


}