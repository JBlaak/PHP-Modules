<?php
/**
 * This is the actual modules.php file checking our very own library!
 */

/* Dependencies */
$phpparser = \PhpModules\Lib\Module::create('PhpParser');
$graph = \PhpModules\Lib\Module::create('Fhaculty\Graph');
$graphviz = \PhpModules\Lib\Module::create('Graphp\GraphViz');

$dependencies = [$phpparser, $graph, $graphviz];

/* Internal modules */
$lib = \PhpModules\Lib\Module::create('PhpModules\Lib', [$phpparser]);
$cli = \PhpModules\Lib\Module::create('PhpModules\Cli', [$lib, $graph, $graphviz]);

$internal = [$lib, $cli];

return \PhpModules\Lib\Modules::create('./src', array_merge($dependencies, $internal));
