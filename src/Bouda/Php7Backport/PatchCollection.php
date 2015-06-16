<?php

namespace Bouda\Php7Backport;


class PatchCollection
{
    private $patches;


    public function add(Patch $patch)
    {

        $this->patches[$patch->getStartPosition()] = $patch;

        $this->removeNestedPatches($patch);
    }


    private function removeNestedPatches(Patch $patch)
    {
        $start = $patch->getStartPosition();
        $end = $patch->getOriginalEndPosition();

        // delete all patches starting between the start and end of this patch
        $keysToDelete = array_flip(range($start + 1, $end));
        $this->patches = array_diff_key($this->patches, $keysToDelete);
    }


    public function getSorted()
    {
        ksort($this->patches);

        return $this->patches;
    }
}
