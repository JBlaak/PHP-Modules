<?php

namespace PhpModules\Lib;

use PhpModules\Lib\Internal\DefinitionsGatherer;
use PhpModules\Lib\Internal\NamespaceName;

class Modules
{

    /**
     * @param string $path
     * @param Module[] $modules
     */
    private function __construct(private string $path, private array $modules)
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

    public function run(): Result
    {
        $definitionsGatherer = new DefinitionsGatherer();
        $definitions = $definitionsGatherer->gather($this->path);

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
        foreach ($this->modules as $module) {
            if ($module->namespace->isParentOf($namespace)) {
                return $module->allowsImport($import);
            }
        }
        return false;
    }

}

