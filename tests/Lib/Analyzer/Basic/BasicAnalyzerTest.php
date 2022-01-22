<?php

namespace Tests\Lib\Analyzer\Basic;

use PhpModules\Lib\Analyzer;
use PhpModules\Lib\Errors\MissingDependency;
use PhpModules\Lib\Module;
use PhpModules\Lib\Modules;
use PHPUnit\Framework\TestCase;

class BasicAnalyzerTest extends TestCase
{
    const SAMPLE_DIR = __DIR__ . '/Sample';

    const NAMESPACE_MODULEA = 'Sample\ModuleA';
    const NAMESPACE_MODULEB = 'Sample\ModuleB';
    const NAMESPACE_MODULEC = 'Sample\ModuleC';

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
        $this->assertInstanceOf(MissingDependency::class, $result->errors[0]);
        $this->assertEquals('ClassB.php', $result->errors[0]->file->getBasename());
        $this->assertEquals('Sample\ModuleA\ClassA', (string)$result->errors[0]->import);
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
        $this->assertInstanceOf(MissingDependency::class, $result->errors[0]);
        $this->assertEquals('ClassB.php', $result->errors[0]->file->getBasename());
        $this->assertEquals('Sample\ModuleA\ClassA', (string)$result->errors[0]->import);
    }

    public function test_run_ignoreFilenamePattern(): void
    {
        /* Given */
        $sampleB = Module::create(self::NAMESPACE_MODULEB);
        $sampleA = Module::create(self::NAMESPACE_MODULEA);
        $modules = Modules::builder(self::SAMPLE_DIR)
            ->register([$sampleA, $sampleB])
            ->ignoreFilenamePattern('/.*B/i');

        /* When */
        $result = Analyzer::create($modules)->analyze();

        /* Then */
        $this->assertCount(0, $result->errors);
    }

    public function test_run_shouldErrorWhenImportIsAliasedWithoutDependency(): void
    {
        /* Given */
        $sampleC = Module::create(self::NAMESPACE_MODULEC);
        $sampleA = Module::create(self::NAMESPACE_MODULEA);
        $modules = Modules::builder(self::SAMPLE_DIR)
            ->register([$sampleA, $sampleC]);

        /* When */
        $result = Analyzer::create($modules)->analyze();

        /* Then */
        $this->assertCount(1, $result->errors);
        $this->assertEquals('ClassC.php', $result->errors[0]->file->getBasename());
        $this->assertEquals('Sample\ModuleA\ClassA', (string)$result->errors[0]->import);
    }

    public function test_run_shouldNotErrorWhenImportIsAliasedWithDependency(): void
    {
        /* Given */
        $sampleC = Module::create(self::NAMESPACE_MODULEC);
        $sampleA = Module::create(self::NAMESPACE_MODULEA, [$sampleC]);
        $modules = Modules::builder(self::SAMPLE_DIR)
            ->register([$sampleA, $sampleC]);

        /* When */
        $result = Analyzer::create($modules)->analyze();

        /* Then */
        $this->assertCount(1, $result->errors);
        $this->assertEquals('ClassC.php', $result->errors[0]->file->getBasename());
        $this->assertEquals('Sample\ModuleA\ClassA', (string)$result->errors[0]->import);
    }

}

