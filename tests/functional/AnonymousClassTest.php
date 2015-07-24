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

$util->setLogger(new AnonymousClass_HASH(\'test.log\'));

echo $foo;

class AnonymousClass_HASH
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
        $expected = '#' . preg_quote($expected) . '#';
        $expected = str_replace('HASH', '[0-9a-f]{32}', $expected);

        $this->assertRegExp($expected, $this->backporter->port($code));
    }
}
