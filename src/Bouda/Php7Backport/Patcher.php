<?php

namespace Bouda\Php7Backport;


class Patcher
{
    /** @var string */
    private $code;

    /** @var int */
    private $offset = 0;

    /** @var string */
    private static $EOL;


    /**
     * @param string
     */
    public function __construct($code)
    {
        $this->code = $code;

        $this->detectEol();
    }


    /** 
     * Apply patches to code. 
     * 
     * @param Bouda\Php7Backport\PatchCollection
     */
    public function apply(PatchCollection $patches)
    {
        foreach ($patches->getReplacePatches() as $patch)
        {
            $start = $patch->getStartPosition($this->offset);

            $originalLength = $patch->getOriginalLength();
            
            $renderedPatch = $patch->render();

            $newLength = strlen($renderedPatch);

            $this->code = substr_replace($this->code, $renderedPatch, $start, $originalLength);

            $this->offset += $newLength - $originalLength;
        }

        foreach ($patches->getAppendPatches() as $patch)
        {
            $this->code .= $patch->render() . self::$EOL;
        }
    }


    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }


    /**
     * Detect most used end of line sequence.
     */
    private function detectEol()
    {
        $maxCount = 0;
        $bestEol = PHP_EOL;
        
        foreach([
            "\n",
            "\r",
            "\n\r",
            "\r\n",
        ] as $eol)
        {
            if (($count = substr_count($this->code, $eol)) >= $maxCount)
            {
                $maxCount = $count;
                $bestEol = $eol;
            }
        }

        self::$EOL = $bestEol;
    }


    public function getEol()
    {
        return self::$EOL;
    }
}
