<?php

use PhpModules\Lib\Module;
use PhpModules\Lib\Reference;
use PhpModules\Lib\SubModules;

$SectionAModuleAReference = Reference::to('Sample\SectionA\SectionAModuleA');

$SectionBModuleA = Module::strict('Sample\SectionB\SectionBModuleA', []);
$SectionBModuleB = Module::strict('Sample\SectionB\SectionBModuleB', [$SectionAModuleAReference]);

return SubModules::create()->register([
    $SectionBModuleA,
    $SectionBModuleB,
]);