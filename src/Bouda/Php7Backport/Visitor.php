<?php

namespace Bouda\Php7Backport;

use PhpParser;
use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\BinaryOp\Spaceship;


class Visitor extends PhpParser\NodeVisitorAbstract
{
    private $tokens;

    private $changedNodes = [];


    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;
    }


    /**
     * Recognize which nodes to change.
     */
    public function leaveNode(Node $node)
    {
        if ($node instanceof Coalesce)
        {
            $changedNode = Transformation\Coalesce::transform($node);
        }
        elseif ($node instanceof Param
            && isset($node->type->parts[0]) 
            && in_array($node->type->parts[0], ['int', 'float', 'string', 'bool']))
        {
            $changedNode = Transformation\ScalarTypehint::transform($node);
        }
        elseif (($node instanceof Function_ || $node instanceof ClassMethod)
            && isset($node->returnType))
        {
            $changedNode = Transformation\ReturnType::transform($node);
            Transformation\ReturnType::setOriginalEndOfHeaderPosition($node, $this->tokens);
        }
        elseif ($node instanceof Spaceship)
        {
            $changedNode = Transformation\Spaceship::transform($node);
        }
        else
        {
            // nothing to do
            return;
        }

        $this->addChangedNode($changedNode);
        
        return $changedNode->getNode();
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


    private function addChangedNode(ChangedNode $changedNode)
    {

        $this->changedNodes[$this->getNodeId($changedNode->getNode())] = $changedNode;

        $this->removeChangedChildren($changedNode);
    }


    private function removeChangedChildren(ChangedNode $changedNode)
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


    public function getSortedChangedNodes()
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
