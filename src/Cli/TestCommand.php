<?php

namespace PhpModules\Cli;

use PhpModules\Lib\Modules;

class TestCommand
{

    public static function create(): TestCommand
    {
        return new TestCommand();
    }

    public function run(): void
    {
        $modules = require './modules.php';
        if (!$modules instanceof Modules) {
            echo 'Your configuration file should return an instance of ' . Modules::class;
            die(1);
        }
        $result = $modules->run();
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