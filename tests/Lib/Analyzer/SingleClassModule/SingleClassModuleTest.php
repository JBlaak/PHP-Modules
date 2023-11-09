<?php

namespace Tests\Lib\Analyzer\SingleClassModule;

use PhpModules\Lib\Analyzer;
use PhpModules\Lib\Module;
use PhpModules\Lib\Modules;
use PHPUnit\Framework\TestCase;

class SingleClassModuleTest extends TestCase
{

    const SAMPLE_DIR = __DIR__ . '/Sample';

    const NAMESPACE_MODULEA = 'Sample\ModuleA';
    const NAMESPACE_MODULEB = 'Sample\ModuleB';//This is a class rather than a directory/module
    const NAMESPACE_MODULEC = 'Sample\ModuleC';

    public function test_run_definedDependencyShouldBeAllowed(): void
    {
        /* Given */
        // ModuleB is a class that wires ModuleA and ModuleC together
        $sampleA = Module::create(self::NAMESPACE_MODULEA);
        $sampleC = Module::create(self::NAMESPACE_MODULEC);
        $sampleB = Module::create(self::NAMESPACE_MODULEB, [$sampleA, $sampleC]);

        $modules = Modules::create(self::SAMPLE_DIR, [$sampleA, $sampleB, $sampleC]);

        /* When */
        $result = Analyzer::create($modules)->analyze();

        /* Then */
        $this->assertFalse($result->hasErrors());
    }

    public function test_run_missingDependencyShouldNotBeAllowed(): void
    {
        /* Given */
        // ModuleB is a class that wires ModuleA and ModuleC together
        $sampleA = Module::create(self::NAMESPACE_MODULEA);
        $sampleC = Module::create(self::NAMESPACE_MODULEC);
        $sampleB = Module::create(self::NAMESPACE_MODULEB);

        $modules = Modules::create(self::SAMPLE_DIR, [$sampleA, $sampleB, $sampleC]);

        /* When */
        $result = Analyzer::create($modules)->analyze();

        /* Then */
        $this->assertTrue($result->hasErrors());
    }
}