<?php

namespace PhpModules\Lib\Internal;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class ImportsCollector extends NodeVisitorAbstract
{

    public ?Node\Stmt\Namespace_ $namespace = null;

    /**
     * @var Node\Stmt\UseUse[]
     */
    public array $imports = [];

    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Stmt\Namespace_) {
            $this->namespace = $node;
        }
        if ($node instanceof Node\Stmt\Use_) {
            foreach ($node->uses as $use) {
                $this->imports[] = $use;
            }
        }
        return $node;
    }
}