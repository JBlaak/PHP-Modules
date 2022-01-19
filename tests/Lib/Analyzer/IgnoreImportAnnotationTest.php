<?php

namespace Tests\Lib\Analyzer;

use PhpModules\Lib\Analyzer;
use PhpModules\Lib\Module;
use PhpModules\Lib\Modules;

class IgnoreImportAnnotationTest extends AnalyzerTestCase
{

    public function testShouldIgnore(): void
    {
        /* Given */
        $moduleAClassAFileDefinition = $this->file('App\ModuleA', ['App\ModuleA\ClassA']);
        $moduleBClassBFileDefinition = $this->file('App\ModuleB', ['App\ModuleB\ClassB'], ['App\ModuleA\ClassA']);

        $moduleA = Module::create('App\ModuleA');
        $moduleB = Module::create('App\ModuleB', [$moduleA]);
        $modules = Modules::builder('.')->register([$moduleA, $moduleB]);

        /* When */
        $result = Analyzer::create($modules, [$moduleAClassAFileDefinition, $moduleBClassBFileDefinition])->analyze();

        /* Then */
        $this->assertCount(0, $result->errors);
    }

}