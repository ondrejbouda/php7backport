<?php

namespace BoudaTests;

use Tester\Assert;
use Tester\TestCase;
use Bouda\Php7Backport\Backporter;

require_once __DIR__ . '/../../bootstrap.php';


class ScalarTypehintTest extends TestCase
{
    private $backporter;


    public function setUp()
    {
        $this->backporter = new Backporter();
    }
    

    public function testScalarTypeHint()
    {
        $code = '<?php function foo(string $x, SomeClass $y) {}';
        $expected = '<?php function foo($x, SomeClass $y) {}';
        Assert::equal($expected, $this->backporter->port($code));
    }
}


$testCase = new ScalarTypehintTest;
$testCase->run();
