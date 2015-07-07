<?php

require_once __DIR__ . '/BackporterFunctionalTestAbstract.php';


class AnonymousClassTest extends BackporterFunctionalTestAbstract
{
    public function testAnonymousClass()
    {
        $code = '<?php 

$util->setLogger(new class("test.log") {
    function __construct($file) {}
    public function log($msg)
    {
        echo $msg;
    }
});

echo $foo;

';

        $expected = '<?php 

$util->setLogger(new AnonymousClass_1(\'test.log\'));

echo $foo;

class AnonymousClass_1
{
    function __construct($file)
    {
    }
    public function log($msg)
    {
        echo $msg;
    }
}
';
        $this->assertEquals($expected, $this->backporter->port($code));
    }
}
