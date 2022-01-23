<?php

namespace Tests\Lib\Analyzer\ExternalDependency;

use PhpModules\Lib\Analyzer;
use PhpModules\Lib\Errors\MissingDependency;
use PhpModules\Lib\Errors\Undefined;
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
        $this->assertFalse($result->hasErrors());
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
        $errors = $result->getFileSpecificErrors();
        $this->assertCount(1, $errors);
        $this->assertInstanceOf(MissingDependency::class, $errors[0]);
        $this->assertEquals('ClassA.php', basename($errors[0]->getFile()));
        $this->assertStringContainsString('Graphp\GraphViz\GraphViz', $errors[0]->getMessage());
    }

    public function test_run_undefinedModuleShouldGiveProperError(): void
    {
        /* Given */
        $sampleA = Module::create(self::NAMESPACE_MODULEA);
        $modules = Modules::builder(self::SAMPLE_DIR)->register($sampleA);

        /* When */
        $result = Analyzer::create($modules)->analyze();

        /* Then */
        $errors = $result->getFileSpecificErrors();
        $this->assertCount(1, $errors);
        $this->assertInstanceOf(Undefined::class, $errors[0]);
        $this->assertEquals('ClassA.php', basename($errors[0]->getFile()));
        $this->assertStringContainsString('Graphp\GraphViz\GraphViz', $errors[0]->getMessage());
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
        $this->assertFalse($result->hasErrors());
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
        $errors = $result->getFileSpecificErrors();
        $this->assertCount(1, $errors);
        $this->assertInstanceOf(MissingDependency::class, $errors[0]);
        $this->assertEquals('ClassB.php', basename($errors[0]->getFile()));
        $this->assertStringContainsString('Sample\ModuleA\ClassA', $errors[0]->getMessage());
    }
}

