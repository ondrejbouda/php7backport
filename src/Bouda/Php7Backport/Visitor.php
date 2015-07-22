<?php

namespace Bouda\Php7Backport;

use PhpParser;
use PhpParser\Node;


/**
 * Abstract Visitor class with common constructor. 
 *  
 * Child classes must implement method leaveNode and transform.
 */
abstract class Visitor extends PhpParser\NodeVisitorAbstract
{
    /** @var Bouda\Php7Backport\PatchFactory */
    protected $patchFactory;

    /** @var Bouda\Php7Backport\PatchCollection */
    protected $patches;


    /**
     * @param Bouda\Php7Backport\PatchFactory 
     * @param Bouda\Php7Backport\PatchCollection
     */
    public function __construct(PatchFactory $patchFactory, PatchCollection $patches)
    {
        $this->patchFactory = $patchFactory;
        $this->patches = $patches;
    }


    /**
     * To be implemented in child Visitor classes for each case of transformation. 
     *  
     * @param PhpParser\Node
     */
    public function leaveNode(Node $node) {}


    protected function tranformAndSave(Node $node)
    {
        $node = $this->transform($node);
        $patch = $this->patchFactory->create($node);
        $this->patches->add($patch);

        return $node;
    }


    /**
     * To be implemented in child Visitor classes for each case of transformation. 
     *  
     * @param PhpParser\Node 
     * @return PhpParser\Node
     */
    abstract protected function transform(Node $node);
}
