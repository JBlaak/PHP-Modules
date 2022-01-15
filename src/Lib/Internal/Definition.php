<?php

namespace PhpModules\Lib\Internal;

class Definition
{
    /**
     * @var \SplFileInfo
     */
    public \SplFileInfo $file;

    /**
     * @var NamespaceName
     */
    public NamespaceName $namespace;

    /**
     * @var array|NamespaceName[]
     */
    public array $imports;

    /**
     * @param \SplFileInfo $file
     * @param NamespaceName $namespace
     * @param NamespaceName[] $imports
     */
    public function __construct(\SplFileInfo $file, NamespaceName $namespace, array $imports)
    {
        $this->file = $file;
        $this->namespace = $namespace;
        $this->imports = $imports;
    }
}