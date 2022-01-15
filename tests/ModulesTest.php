<?php

use PhpModules\Module;
use PhpModules\Modules;
use PHPUnit\Framework\TestCase;

class ModulesTest extends TestCase
{

    const NAMESPACE_MODULEA = 'Sample\ModuleA';
    const NAMESPACE_MODULEB = 'Sample\ModuleB';

    public function test_run_definedDependencyShouldBeAllowed()
    {
        /* Given */
        $sampleA = new Module(self::NAMESPACE_MODULEA);
        $sampleB = new Module(self::NAMESPACE_MODULEB, [$sampleA]);

        /* When */
        $result = Modules::create(__DIR__ . '/Sample', [$sampleA, $sampleB])->run();

        /* Then */
        $this->assertCount(0, $result->errors);
    }

    public function test_run_undefinedDependencyShouldGiveAnError()
    {
        /* Given */
        $sampleA = new Module(self::NAMESPACE_MODULEA);
        $sampleB = new Module(self::NAMESPACE_MODULEB);

        /* When */
        $result = Modules::create(__DIR__ . '/Sample', [$sampleA, $sampleB])->run();

        /* Then */
        $this->assertCount(1, $result->errors);
        $this->assertEquals('ClassB.php', $result->errors[0]->file->getBasename());
        $this->assertEquals('Sample\ModuleA\ClassA', (string)$result->errors[0]->dependency);
    }

    public function test_run_reverseDependencyShouldGiveAnError()
    {
        /* Given */
        $sampleB = new Module(self::NAMESPACE_MODULEB);
        $sampleA = new Module(self::NAMESPACE_MODULEA, [$sampleB]);

        /* When */
        $result = Modules::create(__DIR__ . '/Sample', [$sampleA, $sampleB])->run();

        /* Then */
        $this->assertCount(1, $result->errors);
        $this->assertEquals('ClassB.php', $result->errors[0]->file->getBasename());
        $this->assertEquals('Sample\ModuleA\ClassA', (string)$result->errors[0]->dependency);
    }

}

