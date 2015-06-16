<?php

namespace Bouda\Php7Backport;

use PhpParser\Node;


class PatchFactory
{
    private $tokens;
    private $defaultprinter;


    public function __construct(Tokens $tokens, Printer $defaultprinter)
    {
        $this->tokens = $tokens;
        $this->defaultprinter = $defaultprinter;
    }


    public function create(Node $node, Printer $printer = null)
    {
        $printer = !is_null($printer) ? $printer: $this->defaultprinter;

        return new Patch($this->tokens, $node, $printer);
    }
}
