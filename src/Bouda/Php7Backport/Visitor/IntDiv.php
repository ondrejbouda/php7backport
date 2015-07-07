<?php

namespace Bouda\Php7Backport\Visitor;

use Bouda\Php7Backport;
use Bouda\Php7Backport\Patch;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\BinaryOp\Div;
use PhpParser\Node\Expr\Cast\Int_;


/**
 * Transform intdiv function into division with floor and cast to int.
 *
 * Example: 
 * intdiv(10, 3)
 * becomes
 * (int) floor(10 / 3)
 */
class IntDiv extends Php7Backport\Visitor
{
    public function leaveNode(Node $node)
    {
        if ($node instanceof FuncCall
            && $node->name->parts[0] == 'intdiv')
        {
            return $this->tranformAndSave($node);
        }
    }


    protected function transform(Node $node)
    {
        $node = new Int_(
            new FuncCall(
                new Name('floor'),
                [
                    new Div(
                        $node->args[0]->value,
                        $node->args[1]->value
                    ),
                ]
            ),
            $node->getAttributes() + ['changed' => true]
        );

        return $node;
    }
}
