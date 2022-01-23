<?php

namespace Tests\Lib\Analyzer\StrictPublic;

use PhpModules\Lib\Analyzer;
use PhpModules\Lib\Module;
use PhpModules\Lib\Modules;
use PHPUnit\Framework\TestCase;

class StrictPublicAnalyzerTest extends TestCase
{
    const SAMPLE_DIR = __DIR__ . '/Sample';

    const NAMESPACE_PROJECT = 'Sample';
    const NAMESPACE_MODULEA = 'Sample\ModuleA';
    const NAMESPACE_MODULEB = 'Sample\ModuleB';

    public function test_run_nonStrictModule_canImportNonPublicClass(): void
    {
        /* Given */
        $sampleA = Module::create(self::NAMESPACE_MODULEA, []);
        $sampleB = Module::create(self::NAMESPACE_MODULEB, [$sampleA]);
        $modules = Modules::create(self::SAMPLE_DIR, [$sampleA, $sampleB]);

        /* When */
        $result = Analyzer::create($modules)->analyze();

        /* Then */
        $this->assertFalse($result->hasErrors());
    }

    public function test_run_strictModule_cantImportNonPublicClass(): void
    {
        /* Given */
        $sampleA = Module::strict(self::NAMESPACE_MODULEA, []);
        $sampleB = Module::strict(self::NAMESPACE_MODULEB, [$sampleA]);
        $modules = Modules::create(self::SAMPLE_DIR, [$sampleA, $sampleB]);

        /* When */
        $result = Analyzer::create($modules)->analyze();

        /* Then */
        $errors = $result->getFileSpecificErrors();
        $this->assertCount(1, $errors);
        $this->assertEquals('ClassB.php', basename($errors[0]->getFile()));
        $this->assertStringContainsString('Sample\ModuleA\Internal\InternalClassA', $errors[0]->getMessage());
    }

}

