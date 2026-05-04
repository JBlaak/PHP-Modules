<?php

namespace PhpModules\Lib;

use PhpModules\Attributes\Exposed;

#[Exposed]
class SubModules
{

    /**
     * @param Module[] $modules
     */
    private function __construct(public array $modules)
    {
    }

    /**
     * @param Module[] $modules
     * @return SubModules
     */
    public static function create(array $modules = []): SubModules
    {
        return new SubModules($modules);
    }

    /**
     * @param Module[] $modules
     * @return SubModules
     */
    public function register(array $modules): SubModules
    {
        return new SubModules(array_merge($this->modules, $modules));
    }

}