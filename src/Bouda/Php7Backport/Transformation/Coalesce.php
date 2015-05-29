<?php

namespace Bouda\Php7Backport\Transformation;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Name;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\Isset_;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\Coalesce as CoalesceNode;


class Coalesce
{
    /**
     * Transform null coalesce operator expression into ternary-isset-isnull expression.
     *
     * Example: 
     * $foo ?? $bar
     * becomes
     * isset($foo) && !is_null($foo) ? $foo : $bar
     *
     * @param PhpParser\Node\Expr\BinaryOp\Coalesce $node
     * @return PhpParser\Node
     */
    public static function transform(CoalesceNode $node)
    {
        return new Ternary(
            new BooleanAnd(
                new Isset_([$node->left]), 
                new BooleanNot(
                    new FuncCall(
                        new Name('is_null'),
                        [
                            new Arg($node->left),
                        ]
                    )
                )
            ),
            $node->left,
            $node->right,
            $node->getAttributes() + ['changed' => true]
        );
    }
}
