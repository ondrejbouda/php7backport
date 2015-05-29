<?php

namespace Bouda\Php7Backport;

use PhpParser\Node;


class ChangedNodes
{
    private $changedNodes = [];


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
}