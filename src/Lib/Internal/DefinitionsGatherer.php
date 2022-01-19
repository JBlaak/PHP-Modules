<?php

namespace PhpModules\Lib\Internal;

use PhpModules\Lib\Domain\ClassDefinition;
use PhpModules\Lib\Domain\ClassName;
use PhpModules\Lib\Domain\FileDefinition;
use PhpModules\Lib\Domain\Importable;
use PhpModules\Lib\Domain\NamespaceName;
use PhpModules\Lib\Modules;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;

class DefinitionsGatherer
{
    public function __construct(private Modules $modules)
    {
    }

    /**
     * @return FileDefinition[]
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
            if($this->isIgnored($file)) {
                continue;
            }

            $definitionCollector = new DefinitionCollector();
            $traverser->addVisitor($definitionCollector);

            $code = file_get_contents($file->getPathName());
            if ($code === false) {
                continue;
            }
            $stmts = $parser->parse($code);
            if ($stmts === null) {
                continue;
            }
            $traverser->traverse($stmts);

            $nameSpaceParts = $definitionCollector->namespace?->name?->parts;
            if ($nameSpaceParts !== null) {
                $namespace = NamespaceName::fromArray($nameSpaceParts);

                /** @var Importable[] $imports */
                $imports = [];
                foreach ($definitionCollector->imports as $import) {
                    $imports[] = Importable::fromArray($import->name->parts);
                }

                /** @var ClassDefinition[] $classDefinitions */
                $classDefinitions = [];
                foreach ($definitionCollector->classes as $classStmt) {
                    if ($classStmt->name !== null) {
                        $classDefinition = new ClassDefinition(
                            ClassName::fromNamespaceAndClassName($namespace, $classStmt->name),
                            $classStmt->getDocComment()?->getText()
                        );
                        $classDefinitions[] = $classDefinition;
                    }
                }

                if (count($classDefinitions) === 0) {
                    continue;
                }

                $definitions[] = new FileDefinition(
                    $file,
                    $namespace,
                    $classDefinitions,
                    $imports
                );
            }

            $traverser->removeVisitor($definitionCollector);
        }

        return $definitions;
    }

    private function isIgnored(\SplFileInfo $file):bool
    {
        foreach ($this->modules->ignoredFilenamePatterns as $ignoredFilenamePattern) {
            if(preg_match($ignoredFilenamePattern, $file->getBasename()) === 1) {
                return true;
            }
        }
        return false;
    }

}
