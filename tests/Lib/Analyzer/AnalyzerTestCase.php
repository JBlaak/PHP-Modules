<?php

namespace Tests\Lib\Analyzer;

use PhpModules\Lib\Domain\ClassDefinition;
use PhpModules\Lib\Domain\ClassName;
use PhpModules\Lib\Domain\FileDefinition;
use PhpModules\Lib\Domain\Importable;
use PhpModules\Lib\Domain\NamespaceName;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

class AnalyzerTestCase extends TestCase
{

    /**
     * @param string $namespace
     * @param string[]|ClassDefinition[] $classes
     * @param string[]|Importable[] $imports
     * @return FileDefinition
     */
    protected function file(string $namespace, array $classes, array $imports = []): FileDefinition
    {
        $path = null;
        /** @var ClassDefinition[] $classDefinitions */
        $classDefinitions = [];
        foreach ($classes as $class) {
            if ($path === null) {
                /** @var ClassName|string|null $className */
                $className = null;
                if ($class instanceof ClassDefinition) {
                    $className = $class->className;
                }
                if (is_string($class)) {
                    $className = $class;
                }
                if ($className !== null) {
                    //Transforms namespace App\ModuleA\ClassA to PSR-4 path app/ModuleA/ClassA
                    $path = lcfirst(str_replace('\\', '/', $className));
                }
            }
            if ($class instanceof ClassDefinition) {
                $classDefinitions[] = $class;
            }
            if (is_string($class)) {
                $classDefinitions[] = new ClassDefinition(ClassName::fromString($class), null);
            }
        }

        /** @var Importable[] $importables */
        $importables = [];
        foreach ($imports as $import) {
            if ($import instanceof Importable) {
                $importables[] = $import;
            }
            if (is_string($import)) {
                $importables[] = Importable::fromString($import, null, null);
            }
        }

        assert($path !== null);

        return new FileDefinition(
            new SplFileInfo($path),
            NamespaceName::fromString($namespace),
            $classDefinitions,
            $importables
        );
    }

}