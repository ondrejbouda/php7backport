<?php

require_once __DIR__ . '/BackporterFunctionalTestAbstract.php';


class ReturnTypeTest extends BackporterFunctionalTestAbstract
{
    public function testFunctionReturnType()
    {
        $code = '<?php function foo() : SomeClass {}';
        $expected = '<?php function foo() {}';
        $this->assertEquals($expected, $this->backporter->port($code));
    }

    public function testMethodReturnType()
    {
        $code = '<?php class Foo { public function bar() : SomeClass {} }';
        $expected = '<?php class Foo { public function bar() {} }';
        $this->assertEquals($expected, $this->backporter->port($code));
    }
}
