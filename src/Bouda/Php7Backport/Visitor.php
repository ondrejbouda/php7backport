<?php

namespace Bouda\Php7Backport;

use PhpParser;
use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\BinaryOp\Spaceship;


class Visitor extends PhpParser\NodeVisitorAbstract
{
    private $tokens;

    private $changedNodes;


    public function __construct(array $tokens, ChangedNodes $changedNodes)
    {
        $this->tokens = $tokens;
        $this->changedNodes = $changedNodes;
    }


    /**
     * Recognize which nodes to change.
     */
    public function leaveNode(Node $node)
    {
        if ($node instanceof Coalesce)
        {
            $changedNode = Transformation\Coalesce::transform($node);
        }
        elseif ($node instanceof Param
            && isset($node->type->parts[0]) 
            && in_array($node->type->parts[0], ['int', 'float', 'string', 'bool']))
        {
            $changedNode = Transformation\ScalarTypehint::transform($node);
        }
        elseif (($node instanceof Function_ || $node instanceof ClassMethod)
            && isset($node->returnType))
        {
            $changedNode = Transformation\ReturnType::transform($node);
            Transformation\ReturnType::setOriginalEndOfHeaderPosition($node, $this->tokens);
        }
        elseif ($node instanceof Spaceship)
        {
            $changedNode = Transformation\Spaceship::transform($node);
        }
        elseif ($node instanceof Class_)
        {
            $className = $node->name;

            foreach ($node->stmts as $stmt)
            {
                if ($stmt instanceof ClassMethod && $stmt->name == $className)
                {
                    $changedNode = Transformation\Constructor::transform($stmt);
                    Transformation\Constructor::setOriginalEndOfHeaderPosition($stmt, $this->tokens);
                }
            }
        }

        if (isset($changedNode))
        {
            $this->changedNodes->addNode($changedNode);
        }
    }
}
