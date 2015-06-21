<?php

namespace BoudaTests;

use Tester\Assert;
use Tester\TestCase;
use Bouda\Php7Backport\Backporter;

require_once __DIR__ . '/../../bootstrap.php';


class ConstructorTest extends TestCase
{
    private $backporter;


    public function setUp()
    {
        $this->backporter = new Backporter();
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


$testCase = new ConstructorTest;
$testCase->run();
