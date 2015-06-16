<?php

namespace Bouda\Php7Backport\Visitor;

use Bouda\Php7Backport;
use Bouda\Php7Backport\Patch;

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
            $patch = $this->transform($node);
            $patch->setOriginalEndOfFunctionHeaderPosition();
            $this->patches->add($patch);

            return $patch->getNode();
        }
    }


    private function transform(Stmt $node)
    {
        $node->returnType = null;
        $node->setAttribute('changed', true);

        return $this->patchFactory->create($node);
    }
}
