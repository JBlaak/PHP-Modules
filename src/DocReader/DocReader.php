<?php

namespace PhpModules\DocReader;


use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
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
        $lexer = new Lexer();
        $constExprParser = new ConstExprParser();
        $phpDocParser = new PhpDocParser(new TypeParser($constExprParser), $constExprParser);
        $tokenize = $lexer->tokenize($phpdoc);

        $phpDocNode = $phpDocParser->parse(new TokenIterator($tokenize));

        return count($phpDocNode->getTagsByName('@public')) > 0;
    }

}