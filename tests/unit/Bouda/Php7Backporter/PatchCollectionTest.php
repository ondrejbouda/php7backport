<?php

use Bouda\Php7Backport\PatchCollection;


class PatchCollectionTest extends PHPUnit_Framework_TestCase
{
    private $patches;
    private $patch0, $patch1, $patch5;

    public function setUp()
    {
        $this->patches = new PatchCollection();

        $this->patch0 = $this->getMockBuilder('Bouda\Php7Backport\Patch')->getMock();
        $this->patch0->method('getStartPosition')->willReturn(0);
        $this->patch0->method('getOriginalEndPosition')->willReturn(2);

        $this->patch1 = $this->getMockBuilder('Bouda\Php7Backport\Patch')->getMock();
        $this->patch1->method('getStartPosition')->willReturn(1);
        $this->patch1->method('getOriginalEndPosition')->willReturn(2);

        $this->patch5 = $this->getMockBuilder('Bouda\Php7Backport\Patch')->getMock();
        $this->patch5->method('getStartPosition')->willReturn(5);
        $this->patch5->method('getOriginalEndPosition')->willReturn(7);
    }


    public function testAdd()
    {
        $this->patches->add($this->patch0);

        $this->assertArrayHasKey(0, $this->patches->getReplacePatches());
    }


    public function testGetReplacePatches()
    {
        $this->patches->add($this->patch5);
        $this->patches->add($this->patch0);

        $this->assertEquals([0, 5], array_keys($this->patches->getReplacePatches()));
    }


    public function testRemoveNested()
    {
        $this->patches->add($this->patch1);
        $this->patches->add($this->patch0);

        $this->assertArrayHasKey(0, $this->patches->getReplacePatches());
        $this->assertArrayNotHasKey(1, $this->patches->getReplacePatches());
    }
}
