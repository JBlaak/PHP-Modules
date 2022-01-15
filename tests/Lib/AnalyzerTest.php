<?php

namespace Lib;

use PhpModules\Lib\Analyzer;
use PhpModules\Lib\Module;
use PhpModules\Lib\Modules;
use PHPUnit\Framework\TestCase;

class AnalyzerTest extends TestCase
{
    const SAMPLE_DIR = __DIR__ . '/../Sample';

    const NAMESPACE_MODULEA = 'Sample\ModuleA';
    const NAMESPACE_MODULEB = 'Sample\ModuleB';

    public function test_run_definedDependencyShouldBeAllowed(): void
    {
        /* Given */
        $sampleA = Module::create(self::NAMESPACE_MODULEA);
        $sampleB = Module::create(self::NAMESPACE_MODULEB, [$sampleA]);
        $modules = Modules::create(self::SAMPLE_DIR, [$sampleA, $sampleB]);

        /* When */
        $result = Analyzer::create($modules)->analyze();

        /* Then */
        $this->assertCount(0, $result->errors);
    }

    public function test_run_undefinedDependencyShouldGiveAnError(): void
    {
        /* Given */
        $sampleA = Module::create(self::NAMESPACE_MODULEA);
        $sampleB = Module::create(self::NAMESPACE_MODULEB);
        $modules = Modules::create(self::SAMPLE_DIR, [$sampleA, $sampleB]);

        /* When */
        $result = Analyzer::create($modules)->analyze();

        /* Then */
        $this->assertCount(1, $result->errors);
        $this->assertEquals('ClassB.php', $result->errors[0]->file->getBasename());
        $this->assertEquals('Sample\ModuleA\ClassA', (string)$result->errors[0]->dependency);
    }

    public function test_run_reverseDependencyShouldGiveAnError(): void
    {
        /* Given */
        $sampleB = Module::create(self::NAMESPACE_MODULEB);
        $sampleA = Module::create(self::NAMESPACE_MODULEA, [$sampleB]);
        $modules = Modules::create(self::SAMPLE_DIR, [$sampleA, $sampleB]);

        /* When */
        $result = Analyzer::create($modules)->analyze();

        /* Then */
        $this->assertCount(1, $result->errors);
        $this->assertEquals('ClassB.php', $result->errors[0]->file->getBasename());
        $this->assertEquals('Sample\ModuleA\ClassA', (string)$result->errors[0]->dependency);
    }

}

