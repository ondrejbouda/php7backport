<?php

namespace Bouda\Php7Backport;

use PhpParser;
use PhpParser\Node\Stmt;


abstract class Visitor extends PhpParser\NodeVisitorAbstract
{
    protected $changedNodes;


    public function __construct(ChangedNodes $changedNodes)
    {
        $this->changedNodes = $changedNodes;
    }
}
