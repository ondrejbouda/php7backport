<?php

namespace Bouda\Php7Backport;


class PatchCollection
{
    /** @var array */
    private $patches;


    /**
     * Add a patch to collection. 
     *  
     * @param Bouda\Php7Backport\Patch
     */
    public function add(Patch $patch)
    {

        $this->patches[$patch->getStartPosition()] = $patch;

        $this->removeNestedPatches($patch);
    }


    /**
     * Remove nested patches within this patch. 
     *  
     * @param Bouda\Php7Backport\Patch
     */
    private function removeNestedPatches(Patch $patch)
    {
        $start = $patch->getStartPosition();
        $end = $patch->getOriginalEndPosition();

        // delete all patches starting between the start and end of this patch
        $keysToDelete = array_flip(range($start + 1, $end));
        $this->patches = array_diff_key($this->patches, $keysToDelete);
    }


    /**
     * Get all patches, sorted by position in original source file. 
     *  
     * @return array
     */
    public function getSorted()
    {
        ksort($this->patches);

        return $this->patches;
    }
}
