<?php

namespace PhpModules\Lib\Errors;

/**
 * @public
 */
abstract class Error
{

    public function __construct(
        private string $message,
        private string $file,
        private ?int   $line = null,
    )
    {
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @return int|null
     */
    public function getLine(): ?int
    {
        return $this->line;
    }
}