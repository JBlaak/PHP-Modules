<?php

namespace PhpModules\Lib\Internal;

class NamespaceName
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
     * @param string $namespace
     * @return NamespaceName
     */
    public static function fromString(string $namespace): NamespaceName
    {
        return new NamespaceName($namespace);
    }

    /**
     * @param string[] $parts
     * @return NamespaceName
     */
    public static function fromArray(array $parts): NamespaceName
    {
        return new NamespaceName($parts);
    }

    public function isParentOf(NamespaceName $namespace): bool
    {
        foreach ($this->parts as $key => $part) {
            if (!isset($namespace->parts[$key]) || $namespace->parts[$key] !== $part) {
                return false;
            }
        }
        return true;
    }

    public function __toString()
    {
        return implode('\\', $this->parts);
    }
}