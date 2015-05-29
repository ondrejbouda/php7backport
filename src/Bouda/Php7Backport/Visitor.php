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
    /**
     * Recognize which nodes to change.
     */
    public function leaveNode(Node $node)
    {
        if ($node instanceof Coalesce)
        {
            return Transformation\Coalesce::transform($node);
        }
        elseif ($node instanceof Param
            && isset($node->type->parts[0]) 
            && in_array($node->type->parts[0], ['int', 'float', 'string', 'bool']))
        {
            return Transformation\ScalarTypehint::transform($node);
        }
        elseif (($node instanceof Function_ || $node instanceof ClassMethod)
            && isset($node->returnType))
        {
            return Transformation\ReturnType::transform($node);
        }
        elseif ($node instanceof Spaceship)
        {
            return Transformation\Spaceship::transform($node);
        }
    }
}
