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

    public ?string $phpdoc;

    /**
     * @param string[]|string $name
     * @param string|null $phpdoc
     */
    private function __construct(array|string $name, ?string $phpdoc)
    {
        $this->parts = is_string($name) ? explode('\\', $name) : $name;
        $this->phpdoc = $phpdoc;
    }

    /**
     * @param string $name
     * @param string|null $phpdoc
     * @return Importable
     */
    public static function fromString(string $name, ?string $phpdoc): Importable
    {
        return new Importable($name, $phpdoc);
    }

    /**
     * @param string[] $parts
     * @param string|null $phpdoc
     * @return Importable
     */
    public static function fromArray(array $parts, ?string $phpdoc): Importable
    {
        return new Importable($parts, $phpdoc);
    }

    public function __toString()
    {
        return implode('\\', $this->parts);
    }
}