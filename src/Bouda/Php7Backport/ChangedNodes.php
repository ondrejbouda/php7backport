<?php

namespace Bouda\Php7Backport;

use PhpParser\Node;


class ChangedNodes
{
    private $tokens;

    private $changedNodes = [];


    public function __construct(Tokens $tokens)
    {
        $this->tokens = $tokens;
    }


    public function addNode(ChangedNode $changedNode)
    {

        $this->changedNodes[$this->getNodeId($changedNode->getNode())] = $changedNode;

        $this->removeChildren($changedNode);
    }


    private function getNodeId(Node $node)
    {
        if ($node !== null)
        {
            return $node->getAttribute('startFilePos')
                 . '_'
                 . $node->getAttribute('endFilePos');
        }

        return null;
    }


    private function removeChildren(ChangedNode $changedNode)
    {
        $nodes = $changedNode->getNode();

        array_walk_recursive($nodes, function(&$item) {
            if ($item instanceof Node)
            {
                $node = $item;

                if ($item->getAttribute('changed') === true)
                {
                    $item->setAttribute('changed', false);
                    unset($this->changedNodes[$this->getNodeId($node)]);
                }
            }
        });
    }


    public function getSortedNodes()
    {
        $result = [];

        // reindex by start position
        foreach($this->changedNodes as $key => $changedNode)
        {
            $result[$changedNode->getOriginalStartPosition()] = $changedNode;
        }

        ksort($result);

        return $result;
    }


    /**
     * Find end position of function header declaration in original code 
     * and set to node attribute.
     */
    public function setOriginalEndOfFunctionHeaderPosition(ChangedNode $changedNode)
    {
        $this->tokens->reset();

        $node = $changedNode->getNode();

        $this->tokens->goto($node->getAttribute('startTokenPos'));

        $offset = 0;
        // find the beginning of body of function
        $offset += $this->tokens->findNextToken('{');
        // leave last whitespace before (if present)
        $offset -= $this->tokens->goBackIfToken(T_WHITESPACE);

        $endFilePos = $node->getAttribute('startFilePos') + $offset;

        // lower by 1 to stay consistent with original (wrong) values by parser
        $endFilePos -= 1;

        $node->setAttribute('endFilePos', $endFilePos);
    }
}