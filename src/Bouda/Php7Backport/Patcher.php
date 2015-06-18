<?php

namespace Bouda\Php7Backport;


class Patcher
{
    private $code;

    private $offset = 0;


    /**
     * @param string
     */
    public function __construct($code)
    {
        $this->code = $code;
    }


    /** 
     * Apply patches to code. 
     * 
     * @param Bouda\Php7Backport\PatchCollection
     */
    public function apply(PatchCollection $patches)
    {
        foreach ($patches->getSorted() as $patch)
        {
            $start = $patch->getStartPosition($this->offset);
            
            $originalLength = $patch->getOriginalLength();
            
            $renderedPatch = $patch->render();

            $newLength = strlen($renderedPatch);

            $this->code = substr_replace($this->code, $renderedPatch, $start, $originalLength);

            $this->offset += $newLength - $originalLength;
        }
    }


    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
}
