<?php

namespace Bouda\Php7Backport\Patch;

use Bouda\Php7Backport;
use Bouda\Php7Backport\Printer;
use Bouda\Php7Backport\Tokens;
use PhpParser\Node;


/**
 * {@inheritdoc}
 */
class DefaultPatch implements Php7Backport\Patch
{
    /** @var Bouda\Php7Backport\Tokens */
    protected $tokens;
    /** @var PhpParser\Node */
    protected $node;
    /** @var Bouda\Php7Backport\Printer */
    protected $printer;


    /**
     * @param Bouda\Php7Backport\Tokens
     * @param PhpParser\Node
     * @param Bouda\Php7Backport\Printer
     */
    public function __construct(Tokens $tokens, Node $node, Printer $printer)
    {
        $this->tokens = $tokens;
        $this->node = $node;
        $this->printer = $printer;

        $this->recalculatePosition();
    }


    /**
     * {@inheritdoc}
     */
    public function getStartPosition($offset = 0)
    {
        return $this->node->getAttribute('startFilePos') + $offset;
    }


    protected function getStartTokenPosition()
    {
        return $this->node->getAttribute('startTokenPos');
    }


    /**
     * {@inheritdoc}
     */
    public function getOriginalEndPosition($offset = 0)
    {
        return $this->node->getAttribute('endFilePos') + $offset;
    }


    /**
     * {@inheritdoc}
     */
    public function getOriginalLength()
    {
        return $this->getOriginalEndPosition() + 1 - $this->getStartPosition();
    }


    /**
     * {@inheritdoc}
     */
    public function render()
    {
        return $this->printer->printNode($this->node);
    }


    protected function recalculatePosition() {}
}
