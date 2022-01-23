<?php

namespace PhpModules\Cli\Internal;

use PhpModules\Lib\Modules;

class ModulesResolver
{

    public function get(): Modules
    {
        $path = './modules.php';
        if (!file_exists($path)) {
            echo 'No `modules.php` file found at `' . $path . '`';
            die(1);
        }
        $modules = require $path;
        if (!$modules instanceof Modules) {
            echo 'Your configuration file should return an instance of ' . Modules::class;
            die(1);
        }
        return $modules;
    }

}