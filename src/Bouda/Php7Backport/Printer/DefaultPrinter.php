<?php

namespace Bouda\Php7Backport\Printer;

use Bouda\Php7Backport;
use PhpParser;
use PhpParser\Node;
use PhpParser\Node\Stmt;



class DefaultPrinter
extends PhpParser\PrettyPrinter\Standard
implements Php7Backport\Printer
{
    public function printNode(Node $node)
    {
        return $this->p($node);
    }
}
