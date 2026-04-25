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
$symfonyConsole = Module::create('Symfony\Component\Console');
$ciDetector = Module::create('OndraM\CiDetector');

$dependencies = [$phpparser, $phpdocparser, $graph, $graphviz, $symfonyConsole, $ciDetector];

/* Internal modules */
$attributes = Module::create('PhpModules\Attributes');
$docreader = Module::strict('PhpModules\DocReader', [$phpdocparser, $attributes]);
$exceptions = Module::strict('PhpModules\Exceptions', [$attributes]);
$lib = Module::strict('PhpModules\Lib', [$phpparser, $docreader, $exceptions, $attributes]);
$cli = Module::strict('PhpModules\Cli', [$lib, $graph, $graphviz, $symfonyConsole, $ciDetector, $attributes]);

$internal = [$attributes, $docreader, $exceptions, $lib, $cli];

return Modules::builder(__DIR__ . '/src')
    ->register($dependencies)
    ->register($internal);
