<?php

namespace BoudaTests;

use Tester\Assert;
use Tester\TestCase;
use Bouda\Php7Backport\Backporter;

require_once __DIR__ . '/../../bootstrap.php';


class CoalesceTest extends TestCase
{
    private $backporter;


    public function setUp()
    {
        $this->backporter = new Backporter();
    }


    public function testVariable()
    {
        $code = '<?php $foo ?? $bar;';
        $expected = '<?php isset($foo) ? $foo : $bar;';
        Assert::equal($expected, $this->backporter->port($code));
    }


    public function testClassVariable()
    {
        $code = '<?php $foo->bar ?? $bar;';
        $expected = '<?php isset($foo->bar) ? $foo->bar : $bar;';
        Assert::equal($expected, $this->backporter->port($code));
    }


    public function testStaticVariable()
    {
        $code = '<?php Foo::$bar ?? $bar;';
        $expected = '<?php isset(Foo::$bar) ? Foo::$bar : $bar;';
        Assert::equal($expected, $this->backporter->port($code));
    }


    public function testArrayItem()
    {
        $code = '<?php $array[0] ?? $bar;';
        $expected = '<?php isset($array[0]) ? $array[0] : $bar;';
        Assert::equal($expected, $this->backporter->port($code));
    }


    public function testInteger()
    {
        $code = '<?php 42 ?? $bar;';
        $expected = '<?php !is_null(42) ? 42 : $bar;';
        Assert::equal($expected, $this->backporter->port($code));
    }


    public function testNestedExpression()
    {
        $code = '<?php $foo ?? $bar ?? $baz;';
        $expected = '<?php isset($foo) ? $foo : (isset($bar) ? $bar : $baz);';
        Assert::equal($expected, $this->backporter->port($code));
    }
}


$testCase = new CoalesceTest;
$testCase->run();
