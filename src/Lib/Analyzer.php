<?php

namespace PhpModules\Lib;

use PhpModules\DocReader\DocReader;
use PhpModules\Lib\Domain\FileDefinition;
use PhpModules\Lib\Domain\Importable;
use PhpModules\Lib\Domain\NamespaceName;
use PhpModules\Lib\Internal\DefinitionsGatherer;

/**
 * @public
 */
class Analyzer
{
    /**
     * @param Modules $modules
     * @param FileDefinition[] $definitions
     * @param DocReader $docReader
     */
    private function __construct(
        private Modules   $modules,
        private array     $definitions,
        private DocReader $docReader
    )
    {
    }

    public static function create(Modules $modules): Analyzer
    {
        // TODO move this to a separate step, this is a heavy step you wouldn't expect triggering
        // when calling some simple `create` function
        $definitionsGatherer = new DefinitionsGatherer($modules);
        $definitions = $definitionsGatherer->gather();

        return new Analyzer($modules, $definitions, new DocReader());
    }

    public function analyze(): Result
    {

        /** @var DependencyError[] $errors */
        $errors = [];
        foreach ($this->definitions as $definition) {
            foreach ($definition->imports as $import) {
                if (!$this->isAllowed($definition->namespaceName, $import)) {
                    $errors[] = new DependencyError($definition->file, $import);
                }
            }
        }

        return new Result($errors);
    }

    /**
     * @param NamespaceName $namespace
     * @param Importable $import
     * @return bool
     */
    private function isAllowed(NamespaceName $namespace, Importable $import): bool
    {
        // Check if namespace is part of some module, if not, just return true
        $moduleOfNamespace = $this->getModule($namespace);
        if ($moduleOfNamespace === null) {
            return true;
        }

        // See if import is part of a module
        // if allowed to import undefined modules return true
        $moduleOfImport = $this->getModule($import);
        if ($this->modules->allowUndefinedModules && $moduleOfImport === null) {
            return true;
        }

        // It is always allowed to import from your own module
        if($moduleOfImport === $moduleOfNamespace) {
            return true;
        }

        // If module of import is marked as a strict module the import should be marked as public
        if (
            $moduleOfImport !== null
            && $moduleOfImport->strict
            && !$this->isMarkedAsPublic($import)
        ) {
            return false;
        }

        return $moduleOfNamespace->allowsImport($import);
    }

    private function getModule(NamespaceName|Importable $namespaceName): ?Module
    {
        foreach ($this->modules->modules as $module) {
            if ($module->namespace->isParentOf($namespaceName)) {
                return $module;
            }
        }
        return null;
    }

    private function isMarkedAsPublic(Importable $import): bool
    {
        foreach ($this->definitions as $definition) {
            foreach ($definition->classDefinitions as $classDefinition) {
                if ($classDefinition->className->isEqual($import)) {
                    return $this->docReader->isPublic($classDefinition->phpdoc);
                }
            }
        }

        return true;
    }
}