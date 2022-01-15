<?php

namespace PhpModules\Lib\Internal;

use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;

class DefinitionsGatherer
{
    public function __construct(private string $path)
    {
    }


    /**
     * @return Definition[]
     */
    public function gather(): array
    {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $traverser = new NodeTraverser;

        $recursiveIteratorIterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->path));
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