<?php

namespace Bouda\Php7Backport;

use PhpParser\Node;


/**
 * Represents a patch - string representation of part of code 
 * to be exchanged for transformed one in the original source code.
 */
interface Patch
{
    /**
     * Get position of first char of this patch in the source file,  
     * indexed from 0. 
     *  
     * @param int $offset 
     * @return int
     */
    public function getStartPosition($offset = 0);


    /**
     * Get original position of last char of this patch in the source file,  
     * indexed from 0. 
     *  
     * @param int $offset 
     * @return int
     */
    public function getOriginalEndPosition($offset = 0);


    /**
     * Get length of original part of source code to be patched. 
     *  
     * @return int
     */
    public function getOriginalLength();


    /**
     * Render patch. 
     *  
     * @return string
     */
    public function render();
}
