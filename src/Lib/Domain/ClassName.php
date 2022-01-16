<?php

namespace PhpModules\Lib\Domain;

class ClassName
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
     * @param string $className
     * @return ClassName
     */
    public static function fromString(string $className): ClassName
    {
        return new ClassName($className);
    }

    /**
     * @param string[] $parts
     * @return ClassName
     */
    public static function fromArray(array $parts): ClassName
    {
        return new ClassName($parts);
    }

    public static function fromNamespaceAndClassName(NamespaceName $namespace, string $name): ClassName
    {
        return new ClassName(array_merge($namespace->parts, [$name]));
    }

    public function __toString()
    {
        return implode('\\', $this->parts);
    }

    public function isEqual(ClassName|Importable $other): bool
    {
        if (count($other->parts) !== count($this->parts)) {
            return false;
        }
        foreach ($other->parts as $key => $part) {
            if ($part !== $this->parts[$key]) {
                return false;
            }
        }
        return true;
    }
}