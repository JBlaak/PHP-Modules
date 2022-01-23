<?php

namespace PhpModules\Cli\ErrorFormatter;

use PhpModules\Lib\AnalysisResult;
use PhpModules\Lib\Errors\Error;

class TableErrorFormatter implements ErrorFormatter
{

    public function formatErrors(
        AnalysisResult $analysisResult,
        Output         $output,
    ): int
    {
        $style = $output->getStyle();

        if (!$analysisResult->hasErrors()) {
            $style->success('No errors');

            return 0;
        }

        /** @var array<string, Error[]> $fileErrors */
        $fileErrors = [];
        foreach ($analysisResult->getFileSpecificErrors() as $fileSpecificError) {
            if (!isset($fileErrors[$fileSpecificError->getFile()])) {
                $fileErrors[$fileSpecificError->getFile()] = [];
            }

            $fileErrors[$fileSpecificError->getFile()][] = $fileSpecificError;
        }

        foreach ($fileErrors as $file => $errors) {
            $rows = [];
            foreach ($errors as $error) {
                $message = $error->getMessage();
                $rows[] = [
                    (string)$error->getLine(),
                    $message,
                ];
            }

            $style->table(['Line', $file], $rows);
        }

        if (count($analysisResult->getNotFileSpecificErrors()) > 0) {
            $style->table(['', 'Error'], array_map(static fn(string $error): array => ['', $error], $analysisResult->getNotFileSpecificErrors()));
        }

        $finalMessage = sprintf($analysisResult->getTotalErrorsCount() === 1 ? 'Found %d error' : 'Found %d errors', $analysisResult->getTotalErrorsCount());

        if ($analysisResult->getTotalErrorsCount() > 0) {
            $style->error($finalMessage);
        } else {
            $style->warning($finalMessage);
        }

        return $analysisResult->getTotalErrorsCount() > 0 ? 1 : 0;
    }

}
