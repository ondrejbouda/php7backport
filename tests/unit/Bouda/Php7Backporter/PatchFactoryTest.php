<?php

use Bouda\Php7Backport\PatchFactory;


class PatchFactoryTest extends PHPUnit_Framework_TestCase
{
    private $factory;

    public function setUp()
    {
        $tokens = $this->getMockBuilder('Bouda\Php7Backport\Tokens')
            ->disableOriginalConstructor()->getMock();

        $this->factory = new PatchFactory($tokens);
    }


    public function testCreateDefaultPatch()
    {
        $node = $this->getMockBuilder('PhpParser\Node')->getMock();

        $patch = $this->factory->create($node);
        
        $this->assertInstanceOf('Bouda\Php7Backport\Patch\DefaultPatch', $patch);
    }


    public function testCreateFunctionHeaderPatch()
    {
        $node = $this->getMockBuilder('PhpParser\Node\Stmt\Function_')
            ->disableOriginalConstructor()->getMock();

        $patch = $this->factory->create($node);
        
        $this->assertInstanceOf('Bouda\Php7Backport\Patch\FunctionHeaderPatch', $patch);
    }


    public function testCreateMethodHeaderPatch()
    {
        $node = $this->getMockBuilder('PhpParser\Node\Stmt\ClassMethod')
            ->disableOriginalConstructor()->getMock();

        $patch = $this->factory->create($node);
        
        $this->assertInstanceOf('Bouda\Php7Backport\Patch\FunctionHeaderPatch', $patch);
    }
}
