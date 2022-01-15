<?php

namespace PhpModules\Lib;

class Modules
{

    /**
     * @param string $path
     * @param Module[] $modules
     */
    private function __construct(public string $path, public array $modules)
    {
    }

    /**
     * @param string $path
     * @param Module[] $modules
     * @return Modules
     */
    public static function create(string $path, array $modules): Modules
    {
        return new Modules($path, $modules);
    }

}

