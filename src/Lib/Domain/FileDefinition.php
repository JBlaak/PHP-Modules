<?php

namespace PhpModules\Lib\Domain;

use SplFileInfo;

/**
 * Result of parsing a certain file
 */
class FileDefinition
{

    /**
     * @param SplFileInfo $file
     * @param NamespaceName $namespaceName
     * @param ClassDefinition[] $classDefinitions
     * @param Importable[] $imports
     */
    public function __construct(
        public SplFileInfo  $file,
        public NamespaceName $namespaceName,
        public array         $classDefinitions,
        public array         $imports
    )
    {
    }
}