<?php

use Bouda\Php7Backport\Patch\DefaultPatch;

require_once __DIR__ . '/PatchTestAbstract.php';


class DefaultPatchTest extends PatchTestAbstract
{
    public function setUp()
    {
        parent::setUp();

        $this->patch = new DefaultPatch($this->tokens, $this->node, $this->printer);
    }


    public function testGetStartPosition()
    {
        $this->assertEquals(self::START_FILE_POS, $this->patch->getStartPosition());

        $offset = 3;
        $this->assertEquals($offset, $this->patch->getStartPosition($offset));
    }


    public function testGetOriginalEndPosition()
    {
        $this->assertEquals(self::END_FILE_POS, 
            $this->patch->getOriginalEndPosition());

        $offset = 3;
        $this->assertEquals(self::END_FILE_POS + $offset, 
            $this->patch->getOriginalEndPosition($offset));
    }


    public function testGetOriginalLength()
    {
        $this->assertEquals(self::END_FILE_POS + 1 - self::START_FILE_POS, 
            $this->patch->getOriginalLength());
    }


    public function testRender()
    {
        $this->assertEquals(self::OUTPUT, $this->patch->render());
    }
}
