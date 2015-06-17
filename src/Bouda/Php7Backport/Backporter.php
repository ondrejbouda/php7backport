<?php

namespace Bouda\Php7Backport;

use PhpParser;
use PhpParser\Node;
use Bouda\Php7Backport\Printer\DefaultPrinter;


class Backporter
{
    /** @var Bouda\Php7Backport\Printer */
    private $defaultPrinter;


    public function __construct()
    {
        $this->defaultPrinter = new DefaultPrinter;
    }


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

        $patchFactory = new PatchFactory($tokens, $this->defaultPrinter);
        $patches = new PatchCollection();

        $traverser->addVisitor(new Visitor\Coalesce($patchFactory, $patches));
        $traverser->addVisitor(new Visitor\Constructor($patchFactory, $patches));
        $traverser->addVisitor(new Visitor\ReturnType($patchFactory, $patches));
        $traverser->addVisitor(new Visitor\ScalarTypehint($patchFactory, $patches));
        $traverser->addVisitor(new Visitor\Spaceship($patchFactory, $patches));

        $traverser->traverse($parsedStatements);

        $offset = 0;

        foreach ($patches->getSorted() as $patch)
        {
            $start = $patch->getStartPosition($offset);
            
            $originalLength = $patch->getOriginalLength();
            
            $renderedPatch = $patch->render();

            $newLength = strlen($renderedPatch);

            $code = substr_replace($code, $renderedPatch, $start, $originalLength);

            $offset += $newLength - $originalLength;
        }

        return $code;
    }
}
