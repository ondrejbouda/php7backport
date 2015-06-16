<?php

namespace Bouda\Php7Backport\Visitor;

use Bouda\Php7Backport;
use Bouda\Php7Backport\Patch;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Name;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\Isset_;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\Coalesce as CoalesceNode;


/**
 * Transform null coalesce operator expression into ternary isset/isnull expression. 
 * Isset is used for variables, isnull for expressions.
 *
 * Examples: 
 * 
 * $foo ?? $bar
 * becomes
 * isset($foo) ? $foo : $bar 
 *  
 * 42 ?? $bar
 * becomes
 * !is_null(42) ? 42 : $bar
 */
class Coalesce extends Php7Backport\Visitor
{
    public function leaveNode(Node $node)
    {
        if ($node instanceof CoalesceNode)
        {
            $node = $this->transform($node);
            $patch = $this->patchFactory->create($node);
            $this->patches->add($patch);

            return $node;
        }
    }


    private function transform(CoalesceNode $node)
    {
        // if left node is variable (can be used as an isset argument)
        if ($node->left instanceof Variable
         || $node->left instanceof PropertyFetch
         || $node->left instanceof StaticPropertyFetch
         || $node->left instanceof ArrayDimFetch)
        {
            // isset(x)
            $condition = new Isset_([$node->left]);
        }
        else
        {
            // !is_null(x)
            $condition = new BooleanNot(
                new FuncCall(
                    new Name('is_null'),
                    [
                        new Arg($node->left),
                    ]
                )
            );
        }

        $node = new Ternary(
            $condition,
            $node->left,
            $node->right,
            $node->getAttributes() + ['changed' => true]
        );

        return $node;
    }
}
