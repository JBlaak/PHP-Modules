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

    //Used by neither ModuleA nor ModuleB
    const NAMESPACE_MODULEC = 'Sample\ModuleC';

    //Imports enums from ModuleA
    const NAMESPACE_MODULED = 'Sample\ModuleD';

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

    public function test_run_strictModule_cantHaveUnusedDependencies(): void
    {
        /* Given */
        $sampleC = Module::strict(self::NAMESPACE_MODULEC, []);
        $sampleA = Module::strict(self::NAMESPACE_MODULEA, [$sampleC]);
        $modules = Modules::create(self::SAMPLE_DIR, [$sampleA, $sampleC]);

        /* When */
        $result = Analyzer::create($modules)->analyze();

        /* Then */
        $errors = $result->getFileSpecificErrors();
        $this->assertCount(1, $errors);
    }

    public function test_run_strictModule_cantImportNonPublicEnum(): void
    {
        /* Given */
        $sampleA = Module::strict(self::NAMESPACE_MODULEA, []);
        $sampleD = Module::strict(self::NAMESPACE_MODULED, [$sampleA]);
        $modules = Modules::create(self::SAMPLE_DIR, [$sampleA, $sampleD]);

        /* When */
        $result = Analyzer::create($modules)->analyze();

        /* Then */
        $errors = $result->getFileSpecificErrors();
        $this->assertCount(1, $errors);
        $this->assertEquals('ClassD.php', basename($errors[0]->getFile()));
        $this->assertStringContainsString('Sample\ModuleA\Internal\InternalEnumA', $errors[0]->getMessage());
    }
}
