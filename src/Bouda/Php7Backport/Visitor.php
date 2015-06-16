<?php

namespace Bouda\Php7Backport;

use PhpParser;
use PhpParser\Node\Stmt;


/**
 * Abstract Visitor class with common constructor.
 */
abstract class Visitor extends PhpParser\NodeVisitorAbstract
{
    protected $patchFactory;
    protected $patches;


    public function __construct(PatchFactory $patchFactory, PatchCollection $patches)
    {
        $this->patchFactory = $patchFactory;
        $this->patches = $patches;
    }
}
