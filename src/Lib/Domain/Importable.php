<?php

namespace PhpModules\Lib\Domain;

/**
 * Something that another file might import, such as:
 *
 * use Sample/ClassA
 */
class Importable
{

    /**
     * @var array|string[]
     */
    public array $parts;

    public ?int $line;
    public ?string $phpdoc;

    /**
     * @param string[]|string $name
     * @param int|null $line
     * @param string|null $phpdoc
     */
    private function __construct(array|string $name, ?int $line, ?string $phpdoc)
    {
        $this->parts = is_string($name) ? explode('\\', $name) : $name;
        $this->line = $line;
        $this->phpdoc = $phpdoc;
    }

    /**
     * @param string $name
     * @param int|null $line
     * @param string|null $phpdoc
     * @return Importable
     */
    public static function fromString(string $name, ?int $line, ?string $phpdoc): Importable
    {
        return new Importable($name, $line, $phpdoc);
    }

    /**
     * @param string[] $parts
     * @param int|null $line
     * @param string|null $phpdoc
     * @return Importable
     */
    public static function fromArray(array $parts, ?int $line, ?string $phpdoc): Importable
    {
        return new Importable($parts, $line, $phpdoc);
    }

    public function __toString()
    {
        return implode('\\', $this->parts);
    }
}