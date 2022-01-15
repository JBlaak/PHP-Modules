<?php
/**
 * This is the actual modules.php file checking our very own library!
 */

/* Dependencies */
$phpparser = \PhpModules\Lib\Module::create('PhpParser');

/* Internal modules */
$lib = \PhpModules\Lib\Module::create('PhpModules\Lib', [$phpparser]);
$cli = \PhpModules\Lib\Module::create('PhpModules\Cli', [$lib]);

return \PhpModules\Lib\Modules::create('./src', [$lib, $cli]);
