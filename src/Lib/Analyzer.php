<?php

namespace PhpModules\Lib;

use PhpModules\Lib\Internal\DefinitionsGatherer;
use PhpModules\Lib\Internal\NamespaceName;

class Analyzer
{


    private function __construct(private Modules $modules)
    {
    }

    public static function create(Modules $modules): Analyzer
    {
        return new Analyzer($modules);
    }

    public function analyze(): Result
    {
        $definitionsGatherer = new DefinitionsGatherer($this->modules->path);
        $definitions = $definitionsGatherer->gather();

        /** @var DependencyError[] $errors */
        $errors = [];
        foreach ($definitions as $definition) {
            foreach ($definition->imports as $import) {
                if (!$this->isAllowed($definition->namespace, $import)) {
                    $errors[] = new DependencyError($definition->file, $import);
                }
            }
        }

        return new Result($errors);
    }

    /**
     * @param NamespaceName $namespace
     * @param NamespaceName $import
     * @return bool
     */
    private function isAllowed(NamespaceName $namespace, NamespaceName $import): bool
    {
        if ($this->modules->allowUndefinedModules && !$this->isInModule($import)) {
            return true;
        }

        return $this->moduleAllowsImport($namespace, $import);
    }

    /**
     * @param NamespaceName $namespace
     * @param NamespaceName $import
     * @return bool
     */
    private function moduleAllowsImport(NamespaceName $namespace, NamespaceName $import): bool
    {
        foreach ($this->modules->modules as $module) {
            if ($module->namespace->isParentOf($namespace)) {
                return $module->allowsImport($import);
            }
        }
        return false;
    }

    /**
     * @param NamespaceName $import
     * @return bool
     */
    private function isInModule(NamespaceName $import): bool
    {
        foreach ($this->modules->modules as $module) {
            if ($module->namespace->isParentOf($import)) {
                return true;
            }
        }
        return false;
    }


}