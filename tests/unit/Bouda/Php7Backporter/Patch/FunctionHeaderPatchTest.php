<?php

use Bouda\Php7Backport\Patch\FunctionHeaderPatch;

require_once __DIR__ . '/PatchTestAbstract.php';


class FunctionHeaderPatchTest extends PatchTestAbstract
{
    public function setUp()
    {
        parent::setUp();

        $this->patch = new FunctionHeaderPatch($this->tokens, $this->node, $this->printer);
    }


    public function testGetOriginalEndPosition()
    {
        $this->assertEquals(self::START_FILE_POS + strlen(self::T1.self::T2) - 1, 
            $this->patch->getOriginalEndPosition());

        $offset = 3;
        $this->assertEquals(self::START_FILE_POS + strlen(self::T1.self::T2) - 1 + $offset, 
            $this->patch->getOriginalEndPosition($offset));
    }


    public function testGetOriginalLength()
    {
        $this->assertEquals(self::START_FILE_POS + strlen(self::T1.self::T2) - self::START_FILE_POS, 
            $this->patch->getOriginalLength());
    }
}
