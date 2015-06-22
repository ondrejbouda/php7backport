<?php

require_once __DIR__ . '/BackporterFunctionalTestAbstract.php';


class ScalarTypehintTest extends BackporterFunctionalTestAbstract
{
    public function testScalarTypeHint()
    {
        $code = '<?php function foo(string $x, SomeClass $y) {}';
        $expected = '<?php function foo($x, SomeClass $y) {}';
        $this->assertEquals($expected, $this->backporter->port($code));
    }
}
