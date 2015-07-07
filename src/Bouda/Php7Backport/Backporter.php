<?php

namespace Bouda\Php7Backport;

use PhpParser;
use PhpParser\Node;
use Bouda\Php7Backport\Printer\DefaultPrinter;


class Backporter
{
    /**
     * Backports PHP7 code to PHP5.
     *
     * @param string $code
     * @return string ported code
     * @throws PhpParser\Error
     */
    public function port($code)
    {
        $lexer = new PhpParser\Lexer\Emulative(array(
            'usedAttributes' => array(
                'startFilePos',
                'endFilePos',
                'startTokenPos',
            )
        ));

        $parser = new PhpParser\Parser($lexer);

        $traverser = new PhpParser\NodeTraverser;

        $parsedStatements = $parser->parse($code);

        $tokens = new Tokens($lexer->getTokens());

        $patchFactory = new PatchFactory($tokens);
        $patches = new PatchCollection();

        $traverser->addVisitor(new Visitor\Coalesce($patchFactory, $patches));
        $traverser->addVisitor(new Visitor\Constructor($patchFactory, $patches));
        $traverser->addVisitor(new Visitor\ReturnType($patchFactory, $patches));
        $traverser->addVisitor(new Visitor\ScalarTypehint($patchFactory, $patches));
        $traverser->addVisitor(new Visitor\Spaceship($patchFactory, $patches));
        $traverser->addVisitor(new Visitor\AnonymousClass($patchFactory, $patches));

        $traverser->traverse($parsedStatements);

        $patcher = new Patcher($code);
        $patcher->apply($patches);

        return $patcher->getCode();
    }
}
