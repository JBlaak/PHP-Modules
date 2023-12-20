<?php

namespace Tests\Lib\Analyzer\Project;

use PhpModules\Lib\Analyzer;
use PHPUnit\Framework\TestCase;

class ProjectTest extends TestCase
{

    public function test(): void
    {
        /* Given */
        $modules = require __DIR__ . '/Sample/modules.php';

        /* When */
        $result = Analyzer::create($modules)->analyze();

        /* Then */
        $errors = $result->getFileSpecificErrors();
        $this->assertCount(2, $errors);
        $this->assertEquals('AAClass.php', basename($errors[0]->getFile()));
        $this->assertEquals('BBClass.php', basename($errors[1]->getFile()));
    }

}