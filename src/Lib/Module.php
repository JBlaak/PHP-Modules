<?php

namespace PhpModules\Lib;

use PhpModules\Lib\Domain\Importable;
use PhpModules\Lib\Domain\NamespaceName;

/**
 * @public
 */
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
     * @var bool
     */
    public bool $strict;

    /**
     * @param NamespaceName|string $namespace
     * @param Module[] $dependencies
     * @param bool $strict
     */
    private function __construct(NamespaceName|string $namespace, array $dependencies, bool $strict)
    {
        $this->namespace = is_string($namespace) ? NamespaceName::fromString($namespace) : $namespace;
        $this->dependencies = $dependencies;
        $this->strict = $strict;
    }

    /**
     * @param NamespaceName|string $namespace
     * @param Module[] $dependencies
     * @return Module
     */
    public static function create(NamespaceName|string $namespace, array $dependencies = []): Module
    {
        return new Module($namespace, $dependencies, false);
    }

    /**
     * @param NamespaceName|string $namespace
     * @param Module[] $dependencies
     * @return Module
     */
    public static function strict(NamespaceName|string $namespace, array $dependencies = []): Module
    {
        return new Module($namespace, $dependencies, true);
    }

    public function __toString()
    {
        return $this->namespace->__toString();
    }

}