<?php

namespace Bouda\Php7Backport\Patch;

use Bouda\Php7Backport;
use Bouda\Php7Backport\Printer;
use PhpParser\Node;


/**
 * {@inheritdoc}
 */
class FunctionHeaderPatch extends DefaultPatch
{
    protected function recalculatePosition()
    {
        $this->setOriginalEndOfFunctionHeaderPosition();
    }


    /**
     * Find end position of function header declaration in original code 
     * and set this new end position. 
     *  
     * This is used for replacing only the header of methods/functions, 
     * so that only minimal part of code is affected.
     *  
     * Searches for next '{' token, then goes back if there's whitespace.
     */
    protected function setOriginalEndOfFunctionHeaderPosition()
    {
        $this->tokens->reset();

        $this->tokens->gotoPosition($this->getStartTokenPosition());

        $this->tokens->findNextToken('{');
        $this->tokens->prevIfToken(T_WHITESPACE);
        $this->tokens->prev();

        $offset = $this->tokens->getStringLengthBetweenPositions(
            $this->getStartTokenPosition(), 
            $this->tokens->position());

        $this->node->setAttribute('endFilePos', $this->getStartPosition() + $offset - 1);
    }
}
