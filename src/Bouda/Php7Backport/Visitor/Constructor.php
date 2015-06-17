<?php

namespace Bouda\Php7Backport\Visitor;

use Bouda\Php7Backport;
use Bouda\Php7Backport\Patch;
use Bouda\Php7Backport\Printer\FunctionHeaderPrinter;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;


/**
 * Rename PHP4-style constructor to __construct.
 *
 * Example: 
 * class foo() { function Foo() {} }
 * becomes
 * class foo() { function __construct() {} } 
 */
class Constructor extends Php7Backport\Visitor
{
    public function leaveNode(Node $node)
    {
        if ($node instanceof Class_)
        {
            $className = $node->name;

            foreach ($node->stmts as $stmt)
            {
                if ($stmt instanceof ClassMethod && $stmt->name == $className)
                {
                    $node = $this->transform($stmt);
                    $patch = $this->patchFactory->create($node);
                    $this->patches->add($patch);

                    return $node;
                }
            }
        }
    }


    private function transform(ClassMethod $node)
    {
        $node->name = '__construct';
        $node->setAttribute('changed', true);

        return $node;
    }
}
