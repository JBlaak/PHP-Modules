<?php

namespace PhpModules\Lib;

use PhpModules\Lib\Internal\NamespaceName;

class Module
{

    /**
     * @var NamespaceName
     */
    public NamespaceName $namespace;

    /**
     * @var Module[]
     */
    public array $dependencies = [];

    /**
     * @param NamespaceName|string $namespace
     * @param Module[] $dependencies
     */
    private function __construct(NamespaceName|string $namespace, array $dependencies)
    {
        $this->namespace = is_string($namespace) ? NamespaceName::fromString($namespace) : $namespace;
        $this->dependencies = $dependencies;
    }

    /**
     * @param NamespaceName|string $namespace
     * @param Module[] $dependencies
     * @return Module
     */
    public static function create(NamespaceName|string $namespace, array $dependencies = []): Module
    {
        return new Module($namespace, $dependencies);
    }

    public function allowsImport(NamespaceName $import): bool
    {
        return $this->namespace->isParentOf($import) || $this->isDependency($import);
    }

    private function isDependency(NamespaceName $import): bool
    {
        foreach ($this->dependencies as $dependency) {
            if ($dependency->namespace->isParentOf($import)) {
                return true;
            }
        }
        return false;
    }

}