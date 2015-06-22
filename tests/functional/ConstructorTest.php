<?php

require_once __DIR__ . '/BackporterFunctionalTestAbstract.php';


class ConstructorTest extends BackporterFunctionalTestAbstract
{
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
        $this->assertEquals($expected, $this->backporter->port($code));
    }
}
