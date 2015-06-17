<?php

namespace Bouda\Php7Backport\Visitor;

use Bouda\Php7Backport;
use Bouda\Php7Backport\Patch;
use Bouda\Php7Backport\Printer\FunctionHeaderPrinter;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\ClassMethod;


/**
 * Remove return types from function or method.
 *
 * Example: 
 * function foo() : string {...
 * becomes
 * function foo() {...
 */
class ReturnType extends Php7Backport\Visitor
{
    public function leaveNode(Node $node)
    {
        if (($node instanceof Function_ || $node instanceof ClassMethod)
            && isset($node->returnType))
        {
            $node = $this->transform($node);
            $patch = $this->patchFactory->create($node);
            $this->patches->add($patch);

            return $node;
        }
    }


    private function transform(Stmt $node)
    {
        $node->returnType = null;
        $node->setAttribute('changed', true);

        return $node;
    }
}
