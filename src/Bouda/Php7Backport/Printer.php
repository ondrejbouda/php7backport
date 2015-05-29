<?php

namespace Bouda\Php7Backport;

use PhpParser;
use PhpParser\Node;
use PhpParser\Node\Stmt;


class Printer extends PhpParser\PrettyPrinter\Standard
{
    /**
     * Print single node. 
     *  
     * @param PhpParser\Node 
     * @return string
     */
    public function printNode(Node $node)
    {
        return $this->p($node);
    }


    public function pStmt_ClassMethod(Stmt\ClassMethod $node)
    {
        return $this->printFunctionHeader($node);
    }


    public function pStmt_Function(Stmt\Function_ $node)
    {
        return $this->printFunctionHeader($node);
    }


    /**
     * Print only header of function.
     */
    protected function printFunctionHeader(Node $node)
    {
        return 'function ' . ($node->byRef ? '&' : '') . $node->name
             . '(' . $this->pCommaSeparated($node->params) . ')';
    }
}
