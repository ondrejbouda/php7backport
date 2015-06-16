<?php

namespace Bouda\Php7Backport\Visitor;

use Bouda\Php7Backport;
use Bouda\Php7Backport\Patch;

use PhpParser\Node;
use PhpParser\Node\Param;


/**
 * Remove scalar typehint from function or method parameter.
 *
 * Example: 
 * function foo(string $x, SomeClass $y) {...
 * becomes
 * function foo($x, SomeClass $y) {...
 */
class ScalarTypehint extends Php7Backport\Visitor
{
    public function leaveNode(Node $node)
    {
        if ($node instanceof Param
            && isset($node->type->parts[0]) 
            && in_array($node->type->parts[0], ['int', 'float', 'string', 'bool']))
        {
            $patch = $this->transform($node);
            $this->patches->add($patch);

            return $patch->getNode();
        }
    }


    private function transform(Param $node)
    {
        $node->type = null;
        $node->setAttribute('changed', true);

        return $this->patchFactory->create($node);
    }
}
