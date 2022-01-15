<?php

namespace PhpModules\Cli;

use PhpModules\Cli\Internal\ModulesResolver;
use PhpModules\Lib\Analyzer;

class TestCommand
{


    public function __construct(private ModulesResolver $modulesResolver)
    {
    }

    public static function create(): TestCommand
    {
        return new TestCommand(new ModulesResolver());
    }

    public function run(): void
    {
        $modules = $this->modulesResolver->get();
        $result = Analyzer::create($modules)->analyze();
        foreach ($result->errors as $error) {
            echo $error->file->getBasename() . ' is not allowed to import from ' . $error->dependency . PHP_EOL;
        }
        if (count($result->errors) > 0) {
            echo PHP_EOL;
            echo "-----";
            echo PHP_EOL;
            echo "\033[31mFound " . count($result->errors) . " errors. \033[0m\n";
            die(1);
        } else {
            echo "\033[32mNo errors! \033[0m\n";
        }
    }
}