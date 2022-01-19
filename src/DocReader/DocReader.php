<?php

namespace PhpModules\DocReader;


use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\ParserException;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;

/**
 * @public
 */
class DocReader
{

    public function isPublic(?string $phpdoc): bool
    {
        if ($phpdoc === null) {
            return false;
        }
        $phpdoc = $this->prepare($phpdoc);
        $lexer = new Lexer();
        $constExprParser = new ConstExprParser();
        $phpDocParser = new PhpDocParser(new TypeParser($constExprParser), $constExprParser);
        $tokenize = $lexer->tokenize($phpdoc);

        $phpDocNode = $phpDocParser->parse(new TokenIterator($tokenize));

        return count($phpDocNode->getTagsByName('@public')) > 0;
    }

    public function isIgnoredImport(?string $phpdoc): bool
    {
        if ($phpdoc === null) {
            return false;
        }
        $phpdoc = $this->prepare($phpdoc);
        try {
            $lexer = new Lexer();
            $constExprParser = new ConstExprParser();
            $phpDocParser = new PhpDocParser(new TypeParser($constExprParser), $constExprParser);
            $tokenize = $lexer->tokenize($phpdoc);

            $phpDocNode = $phpDocParser->parse(new TokenIterator($tokenize));

            return count($phpDocNode->getTagsByName('@modules-ignore-next-line')) > 0;
        } catch (ParserException $e) {
            return false;
        }
    }

    private function prepare(string $phpdoc): string
    {
        if (str_starts_with($phpdoc, '/**')) {
            return $phpdoc;
        }
        if (str_starts_with($phpdoc, '//')) {
            $lines = explode("\n", $phpdoc);
            $result = '/**' . PHP_EOL;
            foreach ($lines as $line) {
                $result .= ' * ' . ltrim($line, '/ ') . PHP_EOL;
            }
            $result .= ' */';
            return $result;
        }
        return $phpdoc;
    }

}