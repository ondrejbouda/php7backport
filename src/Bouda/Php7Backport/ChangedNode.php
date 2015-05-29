<?php

namespace Bouda\Php7Backport;

use PhpParser;
use PhpParser\Node;


class ChangedNode
{
    /** @var PhpParser\Node */
    private $node;

    
    public function __construct(Node $node)
    {
        $this->node = $node;
    }


    public function getNode()
    {
        return $this->node;
    }


    public function getOriginalStartPosition($offset = 0)
    {
        return $this->node->getAttribute('startFilePos') + $offset;
    }


    public function getOriginalEndPosition($offset = 0)
    {
        return $this->node->getAttribute('endFilePos') + 1 + $offset;
    }


    public function getOriginalLength()
    {
        return $this->getOriginalEndPosition() - $this->getOriginalStartPosition();
    }
}
