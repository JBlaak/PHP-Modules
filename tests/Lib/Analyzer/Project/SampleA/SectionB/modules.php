<?php

use PhpModules\Lib\Module;
use PhpModules\Lib\SubModules;

$SectionBModuleA = Module::strict('Sample\SectionB\SectionBModuleA', []);
$SectionBModuleB = Module::strict('Sample\SectionB\SectionBModuleB', []);

return SubModules::create()->register([
    $SectionBModuleA,
    $SectionBModuleB,
]);