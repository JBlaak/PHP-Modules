<?php

namespace Sample\ModuleD;


use Sample\ModuleA\EnumA;
use Sample\ModuleA\Internal\InternalEnumA;

class ClassD
{
    public function run(): void
    {
        //Allowed
        $enumValue = EnumA::VALUE1;

        //Not allowed
        $enumValue2 = InternalEnumA::VALUE2;
    }
}