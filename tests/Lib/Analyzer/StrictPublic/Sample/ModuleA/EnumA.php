<?php

namespace Sample\ModuleA;

use PhpModules\Attributes\Exposed;

#[Exposed]
enum EnumA
{
    case VALUE1;
    case VALUE2;
}
