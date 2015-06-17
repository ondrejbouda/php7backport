<?php

namespace Bouda\Php7Backport;

use Bouda\Php7Backport\Patch\DefaultPatch;
use Bouda\Php7Backport\Patch\FunctionHeaderPatch;
use Bouda\Php7Backport\Printer\DefaultPrinter;
use Bouda\Php7Backport\Printer\FunctionHeaderPrinter;

use PhpParser\Node;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\ClassMethod;


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
    /** @var Bouda\Php7Backport\Printer */
    private $functionHeaderPrinter;


    /**
     * @param Bouda\Php7Backport\Tokens
     * @param Bouda\Php7Backport\Printer default printer to be used
     */
    public function __construct(Tokens $tokens)
    {
        $this->tokens = $tokens;
        $this->defaultprinter = new DefaultPrinter;
        $this->functionHeaderPrinter = new FunctionHeaderPrinter;
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
        if ($node instanceof Function_ || $node instanceof ClassMethod)
        {
            $printer = !is_null($printer) ? $printer: $this->functionHeaderPrinter;
            return new FunctionHeaderPatch($this->tokens, $node, $printer);
        }
        else
        {
            $printer = !is_null($printer) ? $printer: $this->defaultprinter;
            return new DefaultPatch($this->tokens, $node, $printer);
        }
    }
}
