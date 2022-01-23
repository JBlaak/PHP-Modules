<?php

namespace PhpModules\Lib;

use PhpModules\DocReader\DocReader;
use PhpModules\Lib\Domain\FileDefinition;
use PhpModules\Lib\Domain\Importable;
use PhpModules\Lib\Domain\NamespaceName;
use PhpModules\Lib\Errors\Error;
use PhpModules\Lib\Errors\MissingDependency;
use PhpModules\Lib\Errors\NotPublicError;
use PhpModules\Lib\Errors\Undefined;
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

    /**
     * @param Modules $modules
     * @param FileDefinition[] $definitions
     * @return Analyzer
     */
    public static function create(Modules $modules, array $definitions = []): Analyzer
    {
        if (empty($definitions)) {
            $definitionsGatherer = new DefinitionsGatherer($modules);
            $definitions = $definitionsGatherer->gather();
        }

        return new Analyzer($modules, $definitions, new DocReader());
    }

    public function analyze(): AnalysisResult
    {
        /** @var Error[] $errors */
        $errors = [];
        foreach ($this->definitions as $definition) {
            foreach ($definition->imports as $import) {
                $errors = array_merge($errors, $this->getErrors($definition->file, $definition->namespaceName, $import));
            }
        }

        return new AnalysisResult($errors, []);
    }

    /**
     * @param \SplFileInfo $file
     * @param NamespaceName $namespace
     * @param Importable $import
     * @return Error[]
     */
    private function getErrors(\SplFileInfo $file, NamespaceName $namespace, Importable $import): array
    {
        // Make sure the import isn't ignored
        if ($this->docReader->isIgnoredImport($import->phpdoc)) {
            return [];
        }

        // Check if namespace is part of some module, if not, no errors
        $moduleOfNamespace = $this->getModule($namespace);
        if ($moduleOfNamespace === null) {
            return [];
        }

        // See if import is part of a module
        // if allowed to import undefined modules no errors
        $moduleOfImport = $this->getModule($import);
        if ($this->modules->allowUndefinedModules && $moduleOfImport === null) {
            return [];
        }

        // It is always allowed to import from your own module
        if ($moduleOfImport === $moduleOfNamespace) {
            return [];
        }

        // If module of import is marked as a strict module the import should be marked as public
        if (
            $moduleOfImport !== null
            && $moduleOfImport->strict
            && !$this->isMarkedAsPublic($import)
        ) {
            return [new NotPublicError($file, $import, $moduleOfImport)];
        }

        foreach ($moduleOfNamespace->dependencies as $dependency) {
            if ($dependency->namespace->isParentOf($import)) {
                return [];
            }
        }

        if ($moduleOfImport === null) {
            return [new Undefined($file, $import)];
        }
        return [new MissingDependency($file, $import, $moduleOfNamespace, $moduleOfImport)];
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