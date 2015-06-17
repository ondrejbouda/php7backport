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

    /** @var int */
    protected $startPosition;
    /** @var int */
    protected $originalEndPosition;
    /** @var int */
    protected $startTokenPosition;

    /** @var string */
    protected $patch;


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

        $this->recalculatePosition();

        // render patch
        $this->patch = $printer->printNode($node);
    }


    /**
     * {@inheritdoc}
     */
    public function getStartPosition($offset = 0)
    {
        return $this->startPosition + $offset;
    }


    /**
     * {@inheritdoc}
     */
    public function getOriginalEndPosition($offset = 0)
    {
        return $this->originalEndPosition + $offset;
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
    public function getPatch()
    {
        return $this->patch;
    }


    protected function recalculatePosition() {}
}
