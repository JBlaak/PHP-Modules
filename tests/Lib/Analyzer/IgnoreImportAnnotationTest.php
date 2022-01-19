<?php

namespace Tests\Lib\Analyzer;

use PhpModules\Lib\Analyzer;
use PhpModules\Lib\Domain\Importable;
use PhpModules\Lib\Module;
use PhpModules\Lib\Modules;

class IgnoreImportAnnotationTest extends AnalyzerTestCase
{

    public function test_shouldErrorWithoutDependency(): void
    {
        /* Given */
        $moduleAClassAFileDefinition = $this->file('App\ModuleA', ['App\ModuleA\ClassA']);
        $moduleBClassBFileDefinition = $this->file('App\ModuleB', ['App\ModuleB\ClassB'], ['App\ModuleA\ClassA']);

        $moduleA = Module::create('App\ModuleA');
        $moduleB = Module::create('App\ModuleB');
        $modules = Modules::builder('.')->register([$moduleA, $moduleB]);

        /* When */
        $result = Analyzer::create($modules, [$moduleAClassAFileDefinition, $moduleBClassBFileDefinition])->analyze();

        /* Then */
        $this->assertCount(1, $result->errors);
    }

    public function test_shouldSucceedWithIgnoreImportAnnotation(): void
    {
        /* Given */
        $moduleAClassAFileDefinition = $this->file('App\ModuleA', ['App\ModuleA\ClassA']);
        $moduleBClassBFileDefinition = $this->file(
            'App\ModuleB',
            ['App\ModuleB\ClassB'],
            [Importable::fromString('App\ModuleA\ClassA', '/** @modules-ignore-next-line */')]
        );

        $moduleA = Module::create('App\ModuleA');
        $moduleB = Module::create('App\ModuleB');
        $modules = Modules::builder('.')->register([$moduleA, $moduleB]);

        /* When */
        $result = Analyzer::create($modules, [$moduleAClassAFileDefinition, $moduleBClassBFileDefinition])->analyze();

        /* Then */
        $this->assertCount(0, $result->errors);
    }

    public function test_shouldSucceedWithIgnoreImportAnnotation_alternativeSyntax(): void
    {
        /* Given */
        $moduleAClassAFileDefinition = $this->file('App\ModuleA', ['App\ModuleA\ClassA']);
        $moduleBClassBFileDefinition = $this->file(
            'App\ModuleB',
            ['App\ModuleB\ClassB'],
            [Importable::fromString('App\ModuleA\ClassA', '// @modules-ignore-next-line')]
        );

        $moduleA = Module::create('App\ModuleA');
        $moduleB = Module::create('App\ModuleB');
        $modules = Modules::builder('.')->register([$moduleA, $moduleB]);

        /* When */
        $result = Analyzer::create($modules, [$moduleAClassAFileDefinition, $moduleBClassBFileDefinition])->analyze();

        /* Then */
        $this->assertCount(0, $result->errors);
    }

}