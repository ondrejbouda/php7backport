<?php

namespace Bouda\Php7Backport;

use PhpParser\Node;


/**
 * Factory for creating patch instances so as not to have to  
 * manage dependencies everytime.
 */
class PatchFactory
{
    /** @var Bouda\Php7Backport\Tokens */
    private $tokens;
    /** @var Bouda\Php7Backport\Printer */
    private $defaultprinter;


    /**
     * @param Bouda\Php7Backport\Tokens
     * @param Bouda\Php7Backport\Printer default printer to be used
     */
    public function __construct(Tokens $tokens, Printer $defaultprinter)
    {
        $this->tokens = $tokens;
        $this->defaultprinter = $defaultprinter;
    }


    /**
     * Get new instance of Patch created from Node. 
     *  
     * @param PhpParser\Node 
     * @param Bouda\Php7Backport\Printer|null optional special Printer 
     * @return Bouda\Php7Backport\Patch
     */
    public function create(Node $node, Printer $printer = null)
    {
        $printer = !is_null($printer) ? $printer: $this->defaultprinter;

        return new Patch($this->tokens, $node, $printer);
    }
}
