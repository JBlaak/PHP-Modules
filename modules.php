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
$docreader = Module::strict('PhpModules\DocReader', [$phpdocparser]);
$exceptions = Module::strict('PhpModules\Exceptions');
$lib = Module::strict('PhpModules\Lib', [$phpparser, $docreader, $exceptions]);
$cli = Module::strict('PhpModules\Cli', [$lib, $graph, $graphviz, $symfonyConsole, $ciDetector]);

$internal = [$docreader, $exceptions, $lib, $cli];

return Modules::builder(__DIR__ . '/src')
    ->register($dependencies)
    ->register($internal);
