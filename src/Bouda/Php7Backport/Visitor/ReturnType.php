<?php

namespace Bouda\Php7Backport\Visitor;

use Bouda\Php7Backport;
use Bouda\Php7Backport\ChangedNode;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\ClassMethod;


class ReturnType extends Php7Backport\Visitor
{
    public function leaveNode(Node $node)
    {
        if (($node instanceof Function_ || $node instanceof ClassMethod)
            && isset($node->returnType))
        {
            $changedNode = $this->transform($node);
            $this->setOriginalEndOfHeaderPosition($node, $this->tokens);

            $this->changedNodes->addNode($changedNode);
        }
    }

  
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
    private function transform(Stmt $node)
    {
        $node->returnType = null;
        $node->setAttribute('changed', true);

        return new ChangedNode($node);
    }


    /**
     * Find end position of function header declaration in original code 
     * and set to node attribute.
     */
    private function setOriginalEndOfHeaderPosition(Stmt $node, array $tokens)
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
