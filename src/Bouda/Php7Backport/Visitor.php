<?php

namespace Bouda\Php7Backport;

use PhpParser;
use PhpParser\Node\Stmt;


abstract class Visitor extends PhpParser\NodeVisitorAbstract
{
    protected $tokens;

    protected $changedNodes;


    public function __construct(array $tokens, ChangedNodes $changedNodes)
    {
        $this->tokens = $tokens;
        $this->changedNodes = $changedNodes;
    }


    /**
     * Find end position of function header declaration in original code 
     * and set to node attribute.
     */
    protected function setOriginalEndOfFunctionHeaderPosition(Stmt $node)
    {
        $currentTokenPosition = $node->getAttribute('startTokenPos');

        $offset = 0;


        // find first occurence of "{" (start of body)
        do
        {
            $currentToken = $this->tokens[$currentTokenPosition];

            $offset += is_array($currentToken) ? strlen($currentToken[1]) : strlen($currentToken);
            
            $currentTokenPosition++;
        }
        while ($currentToken != "{");

        $currentTokenPosition--;
        $currentToken = $this->tokens[$currentTokenPosition];
        $offset -= is_array($currentToken) ? strlen($currentToken[1]) : strlen($currentToken);

        $currentTokenPosition--;
        $currentToken = $this->tokens[$currentTokenPosition];
        if (is_array($currentToken) && $currentToken[0] == T_WHITESPACE)
        {
            $offset -= is_array($currentToken) ? strlen($currentToken[1]) : strlen($currentToken);
        }


        $endFilePos =  $node->getAttribute('startFilePos') + $offset;

        // lower by 1 to stay consistent with original (wrong) values by parser
        $endFilePos -= 1;

        $node->setAttribute('endFilePos', $endFilePos);
    }
}
