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
    public function enterNode(Node $node)
    {
        if ($node instanceof Coalesce)
        {
            return Transformations::transformNullCoalesce($node);
        }
        elseif ($node instanceof Param)
        {
            return Transformations::removeScalarTypeHint($node);
        }
        elseif ($node instanceof Function_ || $node instanceof ClassMethod)
        {
            return Transformations::removeReturnType($node);
        }
        elseif ($node instanceof Spaceship)
        {
            return Transformations::transformSpaceship($node);
        }
    }
}
