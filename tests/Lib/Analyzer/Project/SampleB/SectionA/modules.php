<?php

use PhpModules\Lib\Module;
use PhpModules\Lib\SubModules;

$sectionAModuleB = Module::strict('Sample\SectionA\SectionAModuleB', []);
$sectionAModuleA = Module::strict('Sample\SectionA\SectionAModuleA', [$sectionAModuleB]);

return SubModules::create()->register([
    $sectionAModuleA,
    $sectionAModuleB,
]);