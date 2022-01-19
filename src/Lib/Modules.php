<?php

namespace PhpModules\Lib;

/**
 * @public
 */
class Modules
{
    /**
     * Allow imports from namespaces that aren't defined as a module; e.g. third party libraries
     * @var bool
     */
    public bool $allowUndefinedModules = false;

    /**
     * Filename patterns that shouldn't be included while analyzing, e.g. a ServiceProvider wiring implementations
     * @var string[]
     */
    public array $ignoredFilenamePatterns = [];

    /**
     * @param string $path
     * @param Module[] $modules
     */
    private function __construct(public string $path, public array $modules = [])
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

    /**
     * @param string $path
     * @return Modules
     */
    public static function builder(string $path): Modules
    {
        return new Modules($path);
    }

    /**
     * @param Module[] $modules
     * @return Modules
     */
    public function register(array|Module $modules): Modules
    {
        if ($modules instanceof Module) {
            $this->modules[] = $modules;
        } else {
            $this->modules = array_merge($this->modules, $modules);
        }

        return $this;
    }

    public function allowUndefinedModules(): Modules
    {
        $this->allowUndefinedModules = true;
        return $this;
    }

    public function ignoreFilenamePattern(string $ignoredFilenamePattern): Modules
    {
        $this->ignoredFilenamePatterns[] = $ignoredFilenamePattern;

        return $this;
    }

}

