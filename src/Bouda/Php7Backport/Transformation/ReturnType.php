<?php

namespace Bouda\Php7Backport\Transformation;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use Bouda\Php7Backport\ChangedNode;


class ReturnType
{
    /**
     * Remove return types from function or method.
     *
     * Example: 
     * function foo() : string {...
     * becomes
     * function foo() {...
     *
     * @param PhpParser\Node\Stmt $node (Function_ or ClassMethod)
     * @return Bouda\Php7Backport\ChangedNode
     */
    public static function transform(Stmt $node)
    {
        $node->returnType = null;
        $node->setAttribute('changed', true);

        return new ChangedNode($node);
    }
}
