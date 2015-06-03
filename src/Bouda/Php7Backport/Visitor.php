<?php

namespace Bouda\Php7Backport;

use PhpParser;


abstract class Visitor extends PhpParser\NodeVisitorAbstract
{
    protected $tokens;

    protected $changedNodes;


    public function __construct(array $tokens, ChangedNodes $changedNodes)
    {
        $this->tokens = $tokens;
        $this->changedNodes = $changedNodes;
    }
}
