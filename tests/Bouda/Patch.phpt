<?php

namespace BoudaTests;

use Tester\Assert;
use Tester\TestCase;
use Bouda\Php7Backport\Patch;
use Bouda\Php7Backport\Tokens;
use Bouda\Php7Backport\Printer;
use PhpParser\Node;
use PhpParser\NodeAbstract;

require_once __DIR__ . '/../bootstrap.php';


class MockPrinter implements Printer
{
    const OUTPUT = 'output';

    public function printNode(Node $node)
    {
        return self::OUTPUT;
    }
}


class MockNode extends NodeAbstract {}


class PatchTest extends TestCase
{
    private $patch;

    const T1 = 'a';
    const T2 = 'bbb';
    const T3 = 'c';

    const START_FILE_POS = 0;
    const END_FILE_POS = 0;
    const START_TOKEN_POS = 0;


    public function setUp()
    {
        $tokens = new Tokens([
            self::T1, self::T2, self::T3
        ]);

        $node = new MockNode([], [
            'startFilePos' => self::START_FILE_POS,
            'endFilePos' => self::END_FILE_POS,
            'startTokenPos' => self::START_TOKEN_POS,
        ]);

        $printer = new MockPrinter;

        $this->patch = new Patch($tokens, $node, $printer);
    }


    public function testGetStartPosition()
    {
        Assert::equal(self::START_FILE_POS, $this->patch->getStartPosition());

        $offset = 3;
        Assert::equal($offset, $this->patch->getStartPosition($offset));
    }


    public function testGetOriginalEndPosition()
    {
        Assert::equal(self::END_FILE_POS, $this->patch->getOriginalEndPosition());

        $offset = 3;
        Assert::equal($offset, $this->patch->getOriginalEndPosition($offset));
    }


    public function testGetPatch()
    {
        Assert::equal(MockPrinter::OUTPUT, $this->patch->getPatch());
    }
}


$testCase = new PatchTest;
$testCase->run();
