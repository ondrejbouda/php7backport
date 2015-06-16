<?php

namespace Bouda\Php7Backport\Printer;

use Bouda\Php7Backport;
use PhpParser;
use PhpParser\Node;
use PhpParser\Node\Stmt;


/**
 * Printer for printing only the header of method/function.
 */
class FunctionHeaderPrinter 
extends DefaultPrinter
{
    public function pStmt_ClassMethod(Stmt\ClassMethod $node)
    {
        return $this->printFunctionHeader($node);
    }


    public function pStmt_Function(Stmt\Function_ $node)
    {
        return $this->printFunctionHeader($node);
    }


    /**
     * Print only header of method/function.
     */
    protected function printFunctionHeader(Node $node)
    {
        return 'function ' . ($node->byRef ? '&' : '') . $node->name
             . '(' . $this->pCommaSeparated($node->params) . ')';
    }
}
