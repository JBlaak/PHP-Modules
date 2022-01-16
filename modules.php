<?php
/**
 * This is the actual modules.php file checking our very own library!
 */

/* Dependencies */

use PhpModules\Lib\Config;
use PhpModules\Lib\Module;
use PhpModules\Lib\Modules;

$phpparser = Module::create('PhpParser');
$graph = Module::create('Fhaculty\Graph');
$graphviz = Module::create('Graphp\GraphViz');

$dependencies = [$phpparser, $graph, $graphviz];

/* Internal modules */
$lib = Module::create('PhpModules\Lib', [$phpparser]);
$cli = Module::create('PhpModules\Cli', [$lib, $graph, $graphviz]);

$internal = [$lib, $cli];

return Modules::builder('./src')
    ->register($dependencies)
    ->register($internal);
