<?php

use PhpModules\Lib\Module;
use PhpModules\Lib\SubModules;

$sectionAModuleA = Module::strict('Sample\SectionA\SectionAModuleA', []);
$sectionAModuleB = Module::strict('Sample\SectionA\SectionAModuleB', []);

return SubModules::create()->register([
    $sectionAModuleA,
    $sectionAModuleB,
]);