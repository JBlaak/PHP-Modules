<?php

namespace PhpModules\Lib;

use PhpModules\Lib\Domain\NamespaceName;

/**
 * @public
 *
 * A reference refers to a module that you depend on but don't have access to the full module definition.
 *
 * This is useful when you want to depend on a module that is defined in a different submodule.
 */
class Reference
{

    /**
     * @var NamespaceName
     */
    public NamespaceName $namespace;

    private function __construct(NamespaceName|string $namespace)
    {
        $this->namespace = is_string($namespace) ? NamespaceName::fromString($namespace) : $namespace;
    }

    public static function to(NamespaceName|string $namespace): Reference
    {
        return new Reference($namespace);
    }
}