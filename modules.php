<?php
/**
 * This is the actual modules.php file checking our very own library!
 */

/* Dependencies */

use PhpModules\Lib\Config;
use PhpModules\Lib\Module;
use PhpModules\Lib\Modules;

$phpparser = Module::create('PhpParser');
$phpdocparser = Module::create('PHPStan\PhpDocParser');
$graph = Module::create('Fhaculty\Graph');
$graphviz = Module::create('Graphp\GraphViz');

$dependencies = [$phpparser, $phpdocparser, $graph, $graphviz];

/* Internal modules */
$docreader = Module::create('PhpModules\DocReader', [$phpdocparser], true);
$lib = Module::create('PhpModules\Lib', [$phpparser, $docreader], true);
$cli = Module::create('PhpModules\Cli', [$lib, $graph, $graphviz], true);

$internal = [$docreader, $lib, $cli];

return Modules::builder('./src')
    ->register($dependencies)
    ->register($internal);
