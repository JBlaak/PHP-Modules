<?php

namespace Tests\Lib\Analyzer\PHPInternal;

use PhpModules\Lib\Analyzer;
use PhpModules\Lib\Module;
use PhpModules\Lib\Modules;
use PHPUnit\Framework\TestCase;

class PHPInternalTest extends TestCase
{
    public function test_run_allowedToImportInternal(): void
    {
        /* Given */
        $sampleA = Module::strict('Sample\ModuleA', []);
        $modules = Modules::create(__DIR__ . '/Sample', [$sampleA]);

        /* When */
        $result = Analyzer::create($modules)->analyze();

        /* Then */
        $this->assertFalse($result->hasErrors());
    }

}