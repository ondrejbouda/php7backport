<?php

namespace Bouda\Php7Backport;

use PhpParser\Node,
	PhpParser\Node\Arg,
	PhpParser\Node\Name,
	PhpParser\Node\Param,
	PhpParser\Node\Stmt,
	PhpParser\Node\Expr\FuncCall,
	PhpParser\Node\Expr\BooleanNot,
	PhpParser\Node\Expr\Isset_,
	PhpParser\Node\Expr\Ternary,
	PhpParser\Node\Expr\BinaryOp\BooleanAnd,
	PhpParser\Node\Expr\BinaryOp\Coalesce;


class Transformations
{
	/**
	 * Transform null coalesce operator expression into ternary-isset-isnull expression.
	 *
	 * Example: 
	 * $one ?? $two
	 * becomes
	 * isset($one) && !is_null($one) ? $one : $two
	 *
	 * @param PhpParser\Node $node
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
				$node->type = NULL;
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
	public static function removeReturnTypes(Stmt $node)
	{
		if (isset($node->returnType))
		{
			$node->returnType = NULL;
		}

		return $node;
	}
}
