<?php

namespace BoudaTests;

use Tester\Assert;
use Tester\TestCase;
use Bouda\Php7Backport\Backporter;
use PhpParser;

require_once __DIR__ . '/../bootstrap.php';


class BackporterTest extends TestCase
{
    private $backporter;


    public function setUp()
    {
        $this->backporter = new Backporter();
    }


    public function testPort()
    {
        $code = '<?php echo "Hello world!";';
        Assert::equal($code, $this->backporter->port($code));
    }
}


$testCase = new BackporterTest;
$testCase->run();
