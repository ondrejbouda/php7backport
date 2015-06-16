<?php

namespace Bouda\Php7Backport;

use PhpParser\Node;


/**
 * Represents a patch - string representation of part of code 
 * to be exchanged for transformed one in the original source code.
 */
class Patch
{
    /** @var Bouda\Php7Backport\Tokens */
    private $tokens;

    /** @var int */
    private $startPosition;
    /** @var int */
    private $originalEndPosition;
    /** @var int */
    private $startTokenPosition;

    /** @var string */
    private $patch;


    /**
     * @param Bouda\Php7Backport\Tokens
     * @param PhpParser\Node
     * @param Bouda\Php7Backport\Printer
     */
    public function __construct(Tokens $tokens, Node $node, Printer $printer)
    {
        $this->tokens = $tokens;

        $this->startPosition = $node->getAttribute('startFilePos');
        $this->originalEndPosition = $node->getAttribute('endFilePos');

        $this->startTokenPosition = $node->getAttribute('startTokenPos');

        // render patch
        $this->patch = $printer->printNode($node);
    }


    /**
     * Get position of first char of this patch in the source file,  
     * indexed from 0. 
     *  
     * @param int $offset 
     * @return int
     */
    public function getStartPosition($offset = 0)
    {
        return $this->startPosition + $offset;
    }


    /**
     * Get original position of last char of this patch in the source file,  
     * indexed from 0. 
     *  
     * @param int $offset 
     * @return int
     */
    public function getOriginalEndPosition($offset = 0)
    {
        return $this->originalEndPosition + $offset;
    }


    /**
     * Get length of original part of source code to be patched. 
     *  
     * @return int
     */
    public function getOriginalLength()
    {
        return $this->getOriginalEndPosition() + 1 - $this->getStartPosition();
    }


    /**
     * Get rendered patch. 
     *  
     * @return string
     */
    public function getPatch()
    {
        return $this->patch;
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
    public function setOriginalEndOfFunctionHeaderPosition()
    {
        $this->tokens->reset();

        $this->tokens->goto($this->startTokenPosition);

        $this->tokens->findNextToken('{');
        $this->tokens->prevIfToken(T_WHITESPACE);
        $this->tokens->prev();

        $offset = $this->tokens->getStringLengthBetweenPositions(
            $this->startTokenPosition, 
            $this->tokens->position());

        $this->originalEndPosition = $this->startPosition + $offset - 1;
    }
}
