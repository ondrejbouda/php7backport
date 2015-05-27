<?php

namespace Bouda\Php7Backport;

use PhpParser,
	PhpParser\Node,
	PhpParser\Node\Param,
	PhpParser\Node\Stmt\Function_,
	PhpParser\Node\Stmt\ClassMethod,
	PhpParser\Node\Expr\BinaryOp\Coalesce,
	PhpParser\Node\Expr\BinaryOp\Spaceship;


class Visitor extends PhpParser\NodeVisitorAbstract
{
	/**
	 * Recognize which nodes to change.
	 */
	public function enterNode(Node $node)
	{
		if ($node instanceof Coalesce)
		{
			return Transformations::transformNullCoalesce($node);
		}
		elseif ($node instanceof Param)
		{
			return Transformations::removeScalarTypeHint($node);
		}
		elseif ($node instanceof Function_ || $node instanceof ClassMethod)
		{
			return Transformations::removeReturnTypes($node);
		}
		elseif ($node instanceof Spaceship)
		{
			return Transformations::transformSpaceship($node);
		}
	}
}
