<?php

namespace BoudaTests;

use Tester\Assert;
use Tester\TestCase;
use Bouda\Php7Backport\Backporter;
use PhpParser;

require_once __DIR__ . '/../bootstrap.php';


class BackporterTest extends TestCase
{
    private $backporter;


    public function setUp()
    {
        $this->backporter = new Backporter();
    }


    public function testCoalesceOperator()
    {
        $code = '<?php $foo ?? $bar;';
        $expected = '<?php isset($foo) && !is_null($foo) ? $foo : $bar;';
        Assert::equal($expected, $this->backporter->port($code));
    }


    public function testScalarTypeHint()
    {
        $code = '<?php function foo(string $x, SomeClass $y) {}';
        $expected = '<?php function foo($x, SomeClass $y) {}';
        Assert::equal($expected, $this->backporter->port($code));
    }


    public function testReturnType()
    {
        $code = '<?php function foo() : SomeClass {}';
        $expected = '<?php function foo() {}';
        Assert::equal($expected, $this->backporter->port($code));
    }


    public function testSpaceshipOperator()
    {
        $code = '<?php $foo <=> $bar;';
        $expected = '<?php $foo > $bar ? 1 : ($foo < $bar ? -1 : 0);';
        Assert::equal($expected, $this->backporter->port($code));
    }
}


$testCase = new BackporterTest;
$testCase->run();
