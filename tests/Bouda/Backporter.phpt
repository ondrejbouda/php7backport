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
        $expected = '<?php isset($foo) ? $foo : $bar;';
        Assert::equal($expected, $this->backporter->port($code));

        $code = '<?php $foo->bar ?? $bar;';
        $expected = '<?php isset($foo->bar) ? $foo->bar : $bar;';
        Assert::equal($expected, $this->backporter->port($code));

        $code = '<?php Foo::$bar ?? $bar;';
        $expected = '<?php isset(Foo::$bar) ? Foo::$bar : $bar;';
        Assert::equal($expected, $this->backporter->port($code));

        $code = '<?php $array[0] ?? $bar;';
        $expected = '<?php isset($array[0]) ? $array[0] : $bar;';
        Assert::equal($expected, $this->backporter->port($code));

        $code = '<?php 42 ?? $bar;';
        $expected = '<?php !is_null(42) ? 42 : $bar;';
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
    return isset($foo) ? $foo : $one > $two ? 1 : ($one < $two ? -1 : 0);
}

';
        Assert::equal($expected, $this->backporter->port($code));
    }


    public function testConstructor()
    {
        $code = '<?php 

class Foo
{
    function Foo($bar) {}
}

';

        $expected = '<?php 

class Foo
{
    function __construct($bar) {}
}

';
        Assert::equal($expected, $this->backporter->port($code));
    }
}


$testCase = new BackporterTest;
$testCase->run();
