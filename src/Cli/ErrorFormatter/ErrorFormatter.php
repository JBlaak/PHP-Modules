<?php

namespace PhpModules\Cli\ErrorFormatter;

use PhpModules\Lib\AnalysisResult;

interface ErrorFormatter
{

    public function formatErrors(
        AnalysisResult $analysisResult,
        Output         $output,
    ): int;
}
