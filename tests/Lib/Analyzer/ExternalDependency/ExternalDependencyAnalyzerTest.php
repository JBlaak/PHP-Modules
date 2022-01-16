<?php

namespace Lib\Analyzer\ExternalDependency;

use PhpModules\Lib\Analyzer;
use PhpModules\Lib\Module;
use PhpModules\Lib\Modules;
use PHPUnit\Framework\TestCase;

class ExternalDependencyAnalyzerTest extends TestCase
{
    const SAMPLE_DIR = __DIR__ . '/Sample';

    const NAMESPACE_PROJECT = 'Sample';
    const NAMESPACE_MODULEA = 'Sample\ModuleA';
    const NAMESPACE_MODULEB = 'Sample\ModuleB';
    const NAMESPACE_EXTERNAL = 'Graphp\GraphViz\GraphViz';

    public function test_run_definedDependencyShouldBeAllowed(): void
    {
        /* Given */
        $external = Module::create(self::NAMESPACE_EXTERNAL);
        $sampleA = Module::create(self::NAMESPACE_MODULEA, [$external]);
        $modules = Modules::create(self::SAMPLE_DIR, [$sampleA, $external]);

        /* When */
        $result = Analyzer::create($modules)->analyze();

        /* Then */
        $this->assertCount(0, $result->errors);
    }

    public function test_run_undefinedDependencyShouldGiveAnError(): void
    {
        /* Given */
        $sampleA = Module::create(self::NAMESPACE_MODULEA);
        $external = Module::create(self::NAMESPACE_EXTERNAL);
        $modules = Modules::builder(self::SAMPLE_DIR)->register($sampleA)->register($external);

        /* When */
        $result = Analyzer::create($modules)->analyze();

        /* Then */
        $this->assertCount(1, $result->errors);
        $this->assertEquals('ClassA.php', $result->errors[0]->file->getBasename());
        $this->assertEquals('Graphp\GraphViz\GraphViz', (string)$result->errors[0]->import);
    }

    public function test_run_allowUndefinedModules_shouldAllowImportsFromOutsideOfDefinedModules(): void
    {
        /* Given */
        $sampleA = Module::create(self::NAMESPACE_MODULEA);
        $modules = Modules::builder(self::SAMPLE_DIR)
            ->register($sampleA)
            ->allowUndefinedModules();

        /* When */
        $result = Analyzer::create($modules)->analyze();

        /* Then */
        $this->assertCount(0, $result->errors);
    }

    public function test_run_allowUndefinedModules_requiresExplicitImportWhenImportIsFromModule(): void
    {
        /* Given */
        $sampleA = Module::create(self::NAMESPACE_MODULEA);
        $sampleB = Module::create(self::NAMESPACE_MODULEB);
        $modules = Modules::builder(self::SAMPLE_DIR)
            ->register($sampleA)
            ->register($sampleB)
            ->allowUndefinedModules();

        /* When */
        $result = Analyzer::create($modules)->analyze();

        /* Then */
        $this->assertCount(1, $result->errors);
        $this->assertEquals('ClassB.php', $result->errors[0]->file->getBasename());
        $this->assertEquals('Sample\ModuleA\ClassA', (string)$result->errors[0]->import);
    }
}

