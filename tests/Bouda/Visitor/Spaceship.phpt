<?php

namespace BoudaTests;

use Tester\Assert;
use Tester\TestCase;
use Bouda\Php7Backport\Backporter;

require_once __DIR__ . '/../../bootstrap.php';


class SpaceshipTest extends TestCase
{
    private $backporter;


    public function setUp()
    {
        $this->backporter = new Backporter();
    }
    

    public function testSpaceship()
    {
        $code = '<?php $foo <=> $bar;';
        $expected = '<?php $foo > $bar ? 1 : ($foo < $bar ? -1 : 0);';
        Assert::equal($expected, $this->backporter->port($code));
    }
}


$testCase = new SpaceshipTest;
$testCase->run();
