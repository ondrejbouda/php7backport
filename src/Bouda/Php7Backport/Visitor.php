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
    private $changedNodes = [];


    /**
     * Recognize which nodes to change.
     */
    public function leaveNode(Node $node)
    {
        if ($node instanceof Coalesce)
        {
            $node = Transformation\Coalesce::transform($node);
        }
        elseif ($node instanceof Param
            && isset($node->type->parts[0]) 
            && in_array($node->type->parts[0], ['int', 'float', 'string', 'bool']))
        {
            $node = Transformation\ScalarTypehint::transform($node);
        }
        elseif (($node instanceof Function_ || $node instanceof ClassMethod)
            && isset($node->returnType))
        {
            $node = Transformation\ReturnType::transform($node);
        }
        elseif ($node instanceof Spaceship)
        {
            $node = Transformation\Spaceship::transform($node);
        }
        else
        {
            // nothing to do
            return;
        }

        $this->changedNodes[$this->getNodeId($node)] = $node;
        return $node;
    }


    private function getNodeId(Node $node)
    {
        return $node->getAttribute('startFilePos') . $node->getAttribute('endFilePos');
    }


    public function getChangedNodes()
    {
        return $this->changedNodes;
    }
}
