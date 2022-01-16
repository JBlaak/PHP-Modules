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

    /**
     * @param string[]|string $name
     */
    public function __construct(array|string $name)
    {
        $this->parts = is_string($name) ? explode('\\', $name) : $name;
    }

    /**
     * @param string $name
     * @return Importable
     */
    public static function fromString(string $name): Importable
    {
        return new Importable($name);
    }

    /**
     * @param string[] $parts
     * @return Importable
     */
    public static function fromArray(array $parts): Importable
    {
        return new Importable($parts);
    }

    public function __toString()
    {
        return implode('\\', $this->parts);
    }
}