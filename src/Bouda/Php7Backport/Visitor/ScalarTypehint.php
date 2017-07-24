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
    private static $INVALID_TYPE_HINTS = [
        'int',
        'float',
        'double',
        'string',
        'bool',
        'boolean',
        'iterable',
    ];


    public function leaveNode(Node $node)
    {
        if ($node instanceof Param
            && isset($node->type->parts[0])
            && in_array($node->type->parts[0], self::$INVALID_TYPE_HINTS, true))
        {
            return $this->tranformAndSave($node);
        }
    }


    protected function transform(Node $node)
    {
        $node->type = null;
        $node->setAttribute('changed', true);

        return $node;
    }
}
