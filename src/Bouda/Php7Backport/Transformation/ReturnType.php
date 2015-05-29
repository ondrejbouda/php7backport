<?php

namespace Bouda\Php7Backport\Transformation;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use Bouda\Php7Backport\ChangedNode;


class ReturnType
{
    /**
     * Remove return types from function or method.
     *
     * Example: 
     * function foo() : string {...
     * becomes
     * function foo() {...
     *
     * @param PhpParser\Node\Stmt $node (Function_ or ClassMethod)
     * @return Bouda\Php7Backport\ChangedNode
     */
    public static function transform(Stmt $node)
    {
        $node->returnType = null;
        $node->setAttribute('changed', true);

        return new ChangedNode($node);
    }


    /**
     * Find end position of function header declaration in original code 
     * and set to node attribute.
     */
    public static function setOriginalEndOfHeaderPosition(Stmt $node, array $tokens)
    {
        $currentTokenPosition = $node->getAttribute('startTokenPos');

        $offset = 0;


        // find first occurence of ":" (start of return type declaration)
        do
        {
            $currentToken = $tokens[$currentTokenPosition];

            $offset += is_array($currentToken) ? strlen($currentToken[1]) : strlen($currentToken);
            
            $currentTokenPosition++;
        }
        while ($currentToken != ":");

        // find first occurence of token T_STRING (end of return type declaration)
        do
        {
            $currentToken = $tokens[$currentTokenPosition];

            $offset += is_array($currentToken) ? strlen($currentToken[1]) : strlen($currentToken);
            
            $currentTokenPosition++;
        }
        while ($currentToken[0] != T_STRING);


        $endFilePos =  $node->getAttribute('startFilePos') + $offset;

        // lower by 1 to stay consistent with original (wrong) values by parser
        $endFilePos -= 1;

        $node->setAttribute('endFilePos', $endFilePos);
    }
}
