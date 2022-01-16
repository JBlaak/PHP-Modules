<?php
/**
 * This is the actual modules.php file checking our very own library!
 */

/* Dependencies */

use PhpModules\Lib\Module;
use PhpModules\Lib\Modules;

$phpparser = Module::create('PhpParser');
$phpdocparser = Module::create('PHPStan\PhpDocParser');
$graph = Module::create('Fhaculty\Graph');
$graphviz = Module::create('Graphp\GraphViz');

$dependencies = [$phpparser, $phpdocparser, $graph, $graphviz];

/* Internal modules */
$docreader = Module::strict('PhpModules\DocReader', [$phpdocparser]);
$lib = Module::strict('PhpModules\Lib', [$phpparser, $docreader]);
$cli = Module::strict('PhpModules\Cli', [$lib, $graph, $graphviz]);

$internal = [$docreader, $lib, $cli];

return Modules::builder('./src')
    ->register($dependencies)
    ->register($internal);
