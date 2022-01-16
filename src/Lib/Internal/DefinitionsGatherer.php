<?php

namespace PhpModules\Lib\Internal;

use PhpModules\Lib\Modules;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;

class DefinitionsGatherer
{
    public function __construct(private Modules $modules)
    {
    }

    /**
     * @return Definition[]
     */
    public function gather(): array
    {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $traverser = new NodeTraverser;

        $recursiveIteratorIterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->modules->path));
        $regexIterator = new \RegexIterator($recursiveIteratorIterator, '/\.php$/');

        $definitions = [];

        /** @var \SplFileInfo $file */
        foreach ($regexIterator as $file) {
            $importsCollector = new ImportsCollector();
            $traverser->addVisitor($importsCollector);

            $code = file_get_contents($file->getPathName());
            if ($code === false) {
                continue;
            }
            $stmts = $parser->parse($code);
            if ($stmts === null) {
                continue;
            }
            $traverser->traverse($stmts);

            /** @var NamespaceName[] $imports */
            $imports = [];
            foreach ($importsCollector->imports as $import) {
                $imports[] = NamespaceName::fromArray($import->name->parts);
            }

            $parts = $importsCollector->namespace?->name?->parts;
            if ($parts !== null) {
                $namespace = NamespaceName::fromArray($parts);
                if ($this->isInModule($namespace)) {
                    $definitions[] = new Definition(
                        $file,
                        $namespace,
                        $imports
                    );
                }
            }

            $traverser->removeVisitor($importsCollector);
        }

        return $definitions;
    }

    /**
     * @param NamespaceName $import
     * @return bool
     */
    private function isInModule(NamespaceName $import): bool
    {
        foreach ($this->modules->modules as $module) {
            if ($module->namespace->isParentOf($import)) {
                return true;
            }
        }
        return false;
    }

}