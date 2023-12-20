<?php

namespace PhpModules\Lib;

use PhpModules\DocReader\DocReader;
use PhpModules\Exceptions\PHPModulesException;
use PhpModules\Lib\Domain\ClassName;
use PhpModules\Lib\Domain\FileDefinition;
use PhpModules\Lib\Domain\Importable;
use PhpModules\Lib\Domain\NamespaceName;
use PhpModules\Lib\Errors\Error;
use PhpModules\Lib\Errors\MissingDependency;
use PhpModules\Lib\Errors\NotPublicError;
use PhpModules\Lib\Errors\Undefined;
use PhpModules\Lib\Errors\UnusedDependency;
use PhpModules\Lib\Internal\DefinitionsGatherer;
use PhpModules\Lib\Internal\ModulesProcessor;
use PhpModules\Lib\Internal\SingleDependency;

/**
 * @public
 */
class Analyzer
{
    /**
     * @param Modules $modules
     * @param FileDefinition[] $fileDefinitions
     * @param DocReader $docReader
     */
    private function __construct(
        private Modules   $modules,
        private array     $fileDefinitions,
        private DocReader $docReader
    )
    {
    }

    /**
     * @param Modules $modules
     * @param FileDefinition[] $fileDefinitions
     * @return Analyzer
     * @throws PHPModulesException
     */
    public static function create(Modules $modules, array $fileDefinitions = []): Analyzer
    {
        $modulesProcessor = new ModulesProcessor($modules);
        $modules = $modulesProcessor->process();

        if (empty($fileDefinitions)) {
            $definitionsGatherer = new DefinitionsGatherer($modules);
            $fileDefinitions = $definitionsGatherer->gather();
        }

        return new Analyzer($modules, $fileDefinitions, new DocReader());
    }

    public function analyze(): AnalysisResult
    {
        $allDependencies = $this->listAllDependencies();

        /** @var Error[] $errors */
        $errors = [];
        foreach ($this->fileDefinitions as $fileDefinition) {
            foreach ($fileDefinition->imports as $import) {
                $errors = array_merge($errors, $this->getErrors($fileDefinition, $import, $allDependencies));
            }
        }

        $errors = array_merge($errors, $this->getErrorsFromUnusedDependencies($allDependencies));

        return new AnalysisResult($errors, []);
    }

    /**
     * @param FileDefinition $fileDefinition
     * @param Importable $import
     * @param SingleDependency[] $allDependencies
     * @return Error[]
     */
    private function getErrors(FileDefinition $fileDefinition, Importable $import, array $allDependencies): array
    {

        // Make sure the import isn't ignored
        if ($this->docReader->isIgnoredImport($import->phpdoc)) {
            return [];
        }

        // Check if file is part of some module, if not, no errors
        $moduleOfFile = $this->findModule($fileDefinition);
        if ($moduleOfFile === null) {
            return [];
        }

        // See if import is part of a module
        // if allowed to import undefined modules no errors
        $moduleOfImport = $this->getModule($import);
        if ($this->modules->allowUndefinedModules && $moduleOfImport === null) {
            return [];
        }

        // It is always allowed to import from your own module
        if ($moduleOfImport === $moduleOfFile) {
            return [];
        }

        //If module of namespace is importing the module of import the dependency is used
        if ($moduleOfImport !== null) {
            $this->markUsed($moduleOfFile, $moduleOfImport, $allDependencies);
        }

        // If module of import is marked as a strict module the import should be marked as public
        if (
            $moduleOfImport !== null
            && $moduleOfImport->strict
            && !$this->isMarkedAsPublic($import)
        ) {
            return [new NotPublicError($fileDefinition->file, $import, $moduleOfImport)];
        }

        // Allow importing classes from the same module
        foreach ($moduleOfFile->dependencies as $dependency) {
            if ($dependency->namespace->isParentOf($import)) {
                return [];
            }
        }

        if ($moduleOfImport === null) {
            return [new Undefined($fileDefinition->file, $import)];
        }
        return [new MissingDependency($fileDefinition->file, $import, $moduleOfFile, $moduleOfImport)];
    }

    private function getModule(NamespaceName|ClassName|Importable $name): ?Module
    {
        foreach ($this->modules->modules as $module) {
            if ($module->namespace->isParentOf($name)) {
                return $module;
            }
        }
        return null;
    }

    private function isMarkedAsPublic(Importable $import): bool
    {
        foreach ($this->fileDefinitions as $definition) {
            foreach ($definition->classDefinitions as $classDefinition) {
                if ($classDefinition->className->isEqual($import)) {
                    return $this->docReader->isPublic($classDefinition->phpdoc);
                }
            }
        }

        return true;
    }

    /**
     * @return SingleDependency[]
     */
    private function listAllDependencies(): array
    {
        /** @var SingleDependency[] $allDependencies */
        $allDependencies = [];
        foreach ($this->modules->modules as $module) {
            foreach ($module->dependencies as $dependency) {
                $singleDependency = new SingleDependency($module, $dependency);
                $allDependencies[] = $singleDependency;
            }
        }
        return $allDependencies;
    }

    /**
     * @param Module $moduleOfNamespace
     * @param Module $moduleOfImport
     * @param SingleDependency[] $allDependencies
     * @return void
     */
    private function markUsed(Module $moduleOfNamespace, Module $moduleOfImport, array $allDependencies)
    {
        foreach ($allDependencies as $dependency) {
            if ($dependency->module === $moduleOfNamespace && $dependency->dependency === $moduleOfImport) {
                $dependency->isUsed = true;
            }
        }
    }

    /**
     * @param SingleDependency[] $allDependencies
     * @return Error[]
     */
    private function getErrorsFromUnusedDependencies(array $allDependencies): array
    {
        $errors = [];
        foreach ($allDependencies as $dependency) {
            if (!$dependency->isUsed) {
                $errors[] = new UnusedDependency($dependency->module, $dependency->dependency);
            }
        }

        return $errors;
    }

    /**
     * @param FileDefinition $definition
     * @return Module|null
     */
    private function findModule(FileDefinition $definition): ?Module
    {
        $moduleOfNamespace = $this->getModule($definition->namespaceName);
        if ($moduleOfNamespace === null) {
            foreach ($definition->classDefinitions as $classDefinition) {
                $moduleOfNamespace = $this->getModule($classDefinition->className);
                if ($moduleOfNamespace !== null) {
                    break;
                }
            }
        }
        return $moduleOfNamespace;
    }
}