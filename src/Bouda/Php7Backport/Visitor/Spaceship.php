<?php

namespace Bouda\Php7Backport\Visitor;

use Bouda\Php7Backport;
use Bouda\Php7Backport\ChangedNode;

use PhpParser\Node;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\BinaryOp\Greater;
use PhpParser\Node\Expr\BinaryOp\Smaller;
use PhpParser\Node\Expr\BinaryOp\Spaceship as SpaceshipNode;
use PhpParser\Node\Scalar\LNumber;


class Spaceship extends Php7Backport\Visitor
{
    public function leaveNode(Node $node)
    {
        if ($node instanceof SpaceshipNode)
        {
            $changedNode = $this->transform($node);

            $this->changedNodes->addNode($changedNode);
        }
    }


    /**
     * Transform spaceship operator expression into ternary-greater-smaller expression.
     *
     * Example: 
     * $foo <=> $bar
     * becomes
     * $foo > $bar ? 1 : ($foo < $bar ? -1 : 0)
     *
     * @param PhpParser\Node\Expr\BinaryOp\Spaceship $node
     * @return Bouda\Php7Backport\ChangedNode
     */
    private function transform(SpaceshipNode $node)
    {
        return new ChangedNode(new Ternary(
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
        ));
    }
}
