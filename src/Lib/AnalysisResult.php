<?php

namespace PhpModules\Lib;

use PhpModules\Lib\Errors\Error;

/**
 * @public
 */
class AnalysisResult
{
    /**
     * @var Error[]
     */
    private array $fileSpecificErrors;

    /**
     * @param Error[] $fileSpecificErrors
     * @param string[] $notFileSpecificErrors
     */
    public function __construct(
        array         $fileSpecificErrors,
        private array $notFileSpecificErrors
    )
    {
        usort(
            $fileSpecificErrors,
            static fn(Error $a, Error $b): int => [
                    $a->getFile(),
                    $a->getLine(),
                    $a->getMessage(),
                ] <=> [
                    $b->getFile(),
                    $b->getLine(),
                    $b->getMessage(),
                ],
        );

        $this->fileSpecificErrors = $fileSpecificErrors;
    }

    public function hasErrors(): bool
    {
        return $this->getTotalErrorsCount() > 0;
    }

    public function getTotalErrorsCount(): int
    {
        return count($this->fileSpecificErrors) + count($this->notFileSpecificErrors);
    }

    /**
     * @return Error[] sorted by their file name, line number and message
     */
    public function getFileSpecificErrors(): array
    {
        return $this->fileSpecificErrors;
    }

    /**
     * @return string[]
     */
    public function getNotFileSpecificErrors(): array
    {
        return $this->notFileSpecificErrors;
    }

}