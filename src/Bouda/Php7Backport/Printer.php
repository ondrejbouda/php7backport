<?php

namespace Bouda\Php7Backport;

use PhpParser;
use PhpParser\Node;


/**
 * Printer for printing single node.
 */
interface Printer
{
    /**
     * Print single node. 
     *  
     * @param PhpParser\Node 
     * @return string
     */
    public function printNode(Node $node);
}
