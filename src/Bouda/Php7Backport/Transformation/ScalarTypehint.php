<?php

namespace Bouda\Php7Backport\Transformation;

use PhpParser\Node;
use PhpParser\Node\Param;
use Bouda\Php7Backport\ChangedNode;


class ScalarTypehint
{
    /**
     * Remove scalar typehint from function or method parameter.
     *
     * Example: 
     * function foo(string $x, SomeClass $y) {...
     * becomes
     * function foo($x, SomeClass $y) {...
     *
     * @param PhpParser\Node\Param $node
     * @return Bouda\Php7Backport\ChangedNode
     */
    public static function transform(Param $node)
    {
        $node->type = null;
        $node->setAttribute('changed', true);

        return new ChangedNode($node);
    }
}
