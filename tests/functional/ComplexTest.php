<?php

require_once __DIR__ . '/BackporterFunctionalTestAbstract.php';


class ComplexTest extends BackporterFunctionalTestAbstract
{
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
        $this->assertEquals($expected, $this->backporter->port($code));
    }
}
