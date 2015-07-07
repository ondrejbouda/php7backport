<?php

use Bouda\Php7Backport\Patch;
use Bouda\Php7Backport\Patcher;
use Bouda\Php7Backport\PatchCollection;


class PatcherTest extends PHPUnit_Framework_TestCase
{
    private $patcher;

    public function setUp()
    {
        $code = '0123456789';

        $this->patcher = new Patcher($code);
    }


    public function testPatcher()
    {
        $patches = new PatchCollection;

        $patch = $this->getMockBuilder('Bouda\Php7Backport\Patch')->getMock();
        $patch->method('getStartPosition')->willReturn(0);
        $patch->method('getOriginalEndPosition')->willReturn(2);
        $patch->method('getOriginalLength')->willReturn(3);
        $patch->method('render')->willReturn('ABC');

        $patches->add($patch);

        $this->patcher->apply($patches);
        
        $this->assertEquals('ABC3456789', $this->patcher->getCode());
    }


    public function testGetEol()
    {
        $patcher = new Patcher("\n \n \r");
        $this->assertEquals("\n", $patcher->getEol());

        $patcher = new Patcher("\r\n \r\n");
        $this->assertEquals("\r\n", $patcher->getEol());
    }
}
