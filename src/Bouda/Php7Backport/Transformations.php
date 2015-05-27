<?php

namespace Bouda\Php7Backport;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\Isset_;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\UnaryMinus;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\BinaryOp\Greater;
use PhpParser\Node\Expr\BinaryOp\Smaller;
use PhpParser\Node\Expr\BinaryOp\Spaceship;
use PhpParser\Node\Scalar\LNumber;


class Transformations
{
    /**
     * Transform null coalesce operator expression into ternary-isset-isnull expression.
     *
     * Example: 
     * $foo ?? $bar
     * becomes
     * isset($foo) && !is_null($foo) ? $foo : $bar
     *
     * @param PhpParser\Node\Expr\BinaryOp\Coalesce $node
     * @return PhpParser\Node
     */
    public static function transformNullCoalesce(Coalesce $node)
    {
        return new Ternary(
            new BooleanAnd(
                new Isset_([$node->left]), 
                new BooleanNot(
                    new FuncCall(
                        new Name('is_null'),
                        [
                            new Arg($node->left),
                        ]
                    )
                )
            ),
            $node->left,
            $node->right
        );
    }


    /**
     * Remove scalar typehint from function or method parameter.
     *
     * Example: 
     * function foo(string $x, SomeClass $y) {...
     * becomes
     * function foo($x, SomeClass $y) {...
     *
     * @param PhpParser\Node\Param $node
     * @return PhpParser\Node
     */
    public static function removeScalarTypeHint(Param $node)
    {
        if (isset($node->type->parts[0]))
        {
            if (in_array($node->type->parts[0], ['int', 'float', 'string', 'bool']))
            {
                $node->type = null;
            }
        }

        return $node;
    }


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
    public static function removeReturnType(Stmt $node)
    {
        if (isset($node->returnType))
        {
            $node->returnType = null;
        }

        return $node;
    }


    /**
     * Transform spaceship operator expression into ternary-greater-smaller expression.
     *
     * Example: 
     * $foo <=> $bar
     * becomes
     * $foo > $bar ? 1 : ($foo < $bar ? -1 : 0)
     *
     * @param PhpParser\Node\Expr\BinaryOp\Spaceship $node
     * @return PhpParser\Node
     */
    public static function transformSpaceship(Spaceship $node)
    {
        return new Ternary(
            new Greater(
                $node->left,
                $node->right
            ),
            new LNumber(1),
            new Ternary(
                new Smaller(
                    $node->left,
                    $node->right
                ),
                new LNumber(-1),
                new LNumber(0)
            )
        );
    }
}
