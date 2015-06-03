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
        // find the beginning of body of function
        $offset += $this->findNextToken($currentTokenPosition, '{');
        // leave last whitespace before (if present)
        $offset -= $this->goBackIfToken($currentTokenPosition, T_WHITESPACE);

        $endFilePos =  $node->getAttribute('startFilePos') + $offset;

        // lower by 1 to stay consistent with original (wrong) values by parser
        $endFilePos -= 1;

        $node->setAttribute('endFilePos', $endFilePos);
    }


    protected function findNextToken(&$currentPosition, $token)
    {
        $offset = 0;

        $currentToken = $this->tokens[$currentPosition];

        while (!$this->isTokenEqual($currentToken, $token))
        {
            $offset += $this->getTokenLength($currentToken);
            
            $currentPosition++;

            $currentToken = $this->tokens[$currentPosition];
        }
        

        return $offset;
    }


    protected function goBackIfToken(&$currentPosition, $token)
    {
        $currentPosition--;
        $currentToken = $this->tokens[$currentPosition];
        
        if ($this->isTokenEqual($currentToken, $token))
        {
            return $this->getTokenLength($currentToken);
        }
    }


    protected function getTokenLength($token)
    {
        return is_array($token) ? strlen($token[1]) : strlen($token);
    }


    protected function isTokenEqual($token, $value)
    {
        if (is_numeric($value))
        {
            return is_array($token) && $token[0] === $value;
        }
        else
        {
            return $token === $value;
        }
    }
}
