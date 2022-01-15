<?php

namespace PhpModules\Internal;

use PhpModules\NamespaceName;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;

class DefinitionsGatherer
{

    /**
     * @param string $path
     * @return Definition[]
     */
    public function gather(string $path): array
    {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $traverser = new NodeTraverser;

        $recursiveIteratorIterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
        $regexIterator = new \RegexIterator($recursiveIteratorIterator, '/\.php$/');

        $definitions = [];

        /** @var \SplFileInfo $file */
        foreach ($regexIterator as $file) {
            $importsCollector = new ImportsCollector();
            $traverser->addVisitor($importsCollector);

            $code = file_get_contents($file->getPathName());
            $stmts = $parser->parse($code);
            $traverser->traverse($stmts);

            /** @var NamespaceName[] $imports */
            $imports = [];
            foreach ($importsCollector->imports as $import) {
                $imports[] = NamespaceName::fromArray($import->name->parts);
            }

            $parts = $importsCollector->namespace?->name->parts;
            if ($parts !== null) {
                $definitions[] = new Definition(
                    $file,
                    NamespaceName::fromArray($parts),
                    $imports
                );
            }

            $traverser->removeVisitor($importsCollector);
        }

        return $definitions;
    }

}