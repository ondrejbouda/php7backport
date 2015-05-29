<?php

namespace Bouda\Php7Backport\Transformation;

use PhpParser\Node;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\BinaryOp\Greater;
use PhpParser\Node\Expr\BinaryOp\Smaller;
use PhpParser\Node\Expr\BinaryOp\Spaceship as SpaceshipNode;
use PhpParser\Node\Scalar\LNumber;


class Spaceship
{
    /**
     * Transform spaceship operator expression into ternary-greater-smaller expression.
     *
     * Example: 
     * $foo <=> $bar
     * becomes
     * $foo > $bar ? 1 : ($foo < $bar ? -1 : 0)
     *
     * @param PhpParser\Node\Expr\BinaryOp\Spaceship $node
     * @return PhpParser\Node
     */
    public static function transform(SpaceshipNode $node)
    {
        return new Ternary(
            new Greater(
                $node->left,
                $node->right
            ),
            new LNumber(1),
            new Ternary(
                new Smaller(
                    $node->left,
                    $node->right
                ),
                new LNumber(-1),
                new LNumber(0)
            ),
            $node->getAttributes() + ['changed' => true]
        );
    }
}
