<?php

use Bouda\Php7Backport\Printer\FunctionHeaderPrinter;


class FunctionHeaderPrinterTest extends PHPUnit_Framework_TestCase
{
    private $printer;

    public function setUp()
    {
        $this->printer = new FunctionHeaderPrinter();
    }


    public function testPrintFunctionNode()
    {
        // function node
        $node = $this->getMockBuilder('PhpParser\Node\Stmt\Function_')
            ->disableOriginalConstructor()->getMock();
        $node->method('getType')->willReturn('Stmt_Function');
        $node->byRef = false;
        $node->name = 'test';
        $node->params = [];

        // should be printed without body
        $this->assertEquals('function test()', $this->printer->printNode($node));
    }


    public function testPrintMethodNode()
    {
        // class method node
        $node = $this->getMockBuilder('PhpParser\Node\Stmt\ClassMethod')
            ->disableOriginalConstructor()->getMock();
        $node->method('getType')->willReturn('Stmt_ClassMethod');
        $node->type = false;
        $node->byRef = false;
        $node->name = 'test';
        $node->params = [];

        // should be printed without body
        $this->assertEquals('function test()', $this->printer->printNode($node));
    }
}
