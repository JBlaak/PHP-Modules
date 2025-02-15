<?php

namespace PhpModules\Lib\Internal;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class DefinitionCollector extends NodeVisitorAbstract
{

    public ?Node\Stmt\Namespace_ $namespace = null;

    /**
     * @var Node\Stmt\Use_[]
     */
    public array $imports = [];

    /**
     * @var Node\Stmt\Class_[]
     */
    public array $classes = [];

    /**
     * @var Node\Stmt\Enum_[]
     */
    public array $enums = [];

    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Stmt\Namespace_) {
            $this->namespace = $node;
        }
        if ($node instanceof Node\Stmt\Use_) {
            $this->imports[] = $node;
        }
        if ($node instanceof Node\Stmt\Class_) {
            $this->classes[] = $node;
        }
        if ($node instanceof Node\Stmt\Enum_) {
            $this->enums[] = $node;
        }
        return $node;
    }
}
