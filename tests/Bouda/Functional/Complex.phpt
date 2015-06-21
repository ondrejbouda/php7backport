<?php

namespace BoudaTests;

use Tester\Assert;
use Tester\TestCase;
use Bouda\Php7Backport\Backporter;
use PhpParser;

require_once __DIR__ . '/../../bootstrap.php';


class ComplexTest extends TestCase
{
    private $backporter;


    public function setUp()
    {
        $this->backporter = new Backporter();
    }


    public function testComplexCode()
    {
        $code = '<?php 

function foo(string $x,   SomeClass $y) : int
{
    // comment
    return $foo ?? $one <=> $two;
}

';

        $expected = '<?php 

function foo($x, SomeClass $y)
{
    // comment
    return isset($foo) ? $foo : ($one > $two ? 1 : ($one < $two ? -1 : 0));
}

';
        Assert::equal($expected, $this->backporter->port($code));
    }
}


$testCase = new ComplexTest;
$testCase->run();
