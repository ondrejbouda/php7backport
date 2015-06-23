<?php

use Bouda\Php7Backport\Printer\DefaultPrinter;


class DefaultPrinterTest extends PHPUnit_Framework_TestCase
{
    private $printer;

    public function setUp()
    {
        $this->printer = new DefaultPrinter();
    }


    public function testPrintNode()
    {
        // random simple node
        $node = $this->getMockBuilder('PhpParser\Node\Scalar\MagicConst\Class_')->getMock();
        $node->method('getType')->willReturn('Scalar_MagicConst_Class');

        $this->assertEquals('__CLASS__', $this->printer->printNode($node));
    }
}
