<?php

namespace Bouda\Php7Backport\Transformation;

use PhpParser\Node;
use PhpParser\Node\Stmt;


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
     * @return PhpParser\Node
     */
    public static function transform(Stmt $node)
    {
        $node->returnType = null;
        $node->setAttribute('changed', true);

        return $node;
    }
}
