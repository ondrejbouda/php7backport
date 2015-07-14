<?php

namespace Bouda\Php7Backport;


class PatchCollection
{
    /** @var array */
    private $replacePatches = [];

    /** @var array */
    private $appendPatches = [];

    /** @var int */
    private $id = 1;


    /**
     * Add a patch to collection. 
     *  
     * @param Bouda\Php7Backport\Patch
     */
    public function add(Patch $patch)
    {
        $this->replacePatches[$patch->getStartPosition()] = $patch;

        $this->removeNestedPatches($patch);
    }


    /**
     * Add a patch to be appended at the end of file. 
     *  
     * @param Bouda\Php7Backport\Patch
     */
    public function append(Patch $patch)
    {
        $this->appendPatches[] = $patch;
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

        if ($start == $end)
        {
            return;
        }

        // delete all patches starting between the start and end of this patch
        $keysToDelete = array_flip(range($start + 1, $end));
        $this->replacePatches = array_diff_key($this->replacePatches, $keysToDelete);
    }


    /**
     * Get all patches, sorted by position in original source file. 
     *  
     * @return array
     */
    public function getReplacePatches()
    {
        ksort($this->replacePatches);

        return $this->replacePatches;
    }


    /**
     * Get all patches to be appended at the end of file. 
     *  
     * @return array
     */
    public function getAppendPatches()
    {
        return $this->appendPatches;
    }


    /**
     * Get id from sequence for generated class/function names. 
     *  
     * @return int
     */
    public function getNextId()
    {
        return $this->id++;
    }
}
