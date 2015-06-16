<?php

namespace Bouda\Php7Backport;

use PhpParser\Node;


class Patch
{
    private $tokens;

    private $node;

    private $startPosition;
    private $originalEndPosition;

    private $startTokenPosition;

    private $patch;

    
    public function __construct(Tokens $tokens, Node $node, Printer $printer)
    {
        $this->tokens = $tokens;

        $this->node = $node;

        $this->startPosition = $node->getAttribute('startFilePos');
        $this->originalEndPosition = $node->getAttribute('endFilePos');

        $this->startTokenPosition = $node->getAttribute('startTokenPos');

        $this->patch = $printer->printNode($node);
    }


    public function getNode()
    {
        return $this->node;
    }


    public function getStartPosition($offset = 0)
    {
        return $this->startPosition + $offset;
    }


    public function getOriginalEndPosition($offset = 0)
    {
        return $this->originalEndPosition + $offset;
    }


    public function getOriginalLength()
    {
        return $this->getOriginalEndPosition() + 1 - $this->getStartPosition();
    }


    public function getPatch()
    {
        return $this->patch;
    }


    /**
     * Find end position of function header declaration in original code.
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
