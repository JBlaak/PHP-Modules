<?php

namespace PhpModules;

class NamespaceName
{

    /**
     * @var array|string[]
     */
    public array $parts;

    public function __construct(array|string $name)
    {
        $this->parts = is_string($name) ? explode('\\', $name) : $name;
    }

    public static function fromString(string $namespace)
    {
        return new NamespaceName($namespace);
    }

    public static function fromArray(array $parts)
    {
        return new NamespaceName($parts);
    }

    public function isParentOf(NamespaceName $namespace)
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