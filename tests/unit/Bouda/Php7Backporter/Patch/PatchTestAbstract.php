<?php

use Bouda\Php7Backport\Tokens;
use Bouda\Php7Backport\Printer;
use PhpParser\NodeAbstract;


class MockNode extends \PhpParser\NodeAbstract {}


class PatchTestAbstract extends PHPUnit_Framework_TestCase
{
    protected $tokens;
    protected $node;
    protected $printer;
    protected $patch;

    const T1 = 'aaaaa';
    const T2 = '  ';
    const T3 = '{';

    const START_FILE_POS = 0;
    const END_FILE_POS = 3;
    const START_TOKEN_POS = 0;

    const OUTPUT = 'output';


    public function setUp()
    {
        $this->tokens = new Tokens([
            self::T1, self::T2, self::T3
        ]);

        $this->node = new MockNode([], [
            'startFilePos' => self::START_FILE_POS,
            'endFilePos' => self::END_FILE_POS,
            'startTokenPos' => self::START_TOKEN_POS,
        ]);

        $this->printer = $this->getMockBuilder('Bouda\Php7Backport\Printer')->getMock();
        $this->printer->method('printNode')->willReturn(self::OUTPUT);
    }
}
