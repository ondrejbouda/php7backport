<?php

use Bouda\Php7Backport\Backporter;


class BackporterTest extends PHPUnit_Framework_TestCase
{
    private $backporter;


    public function setUp()
    {
        $this->backporter = new Backporter();
    }


    public function testPort()
    {
        $code = '<?php echo "Hello world!";';
        $this->assertEquals($code, $this->backporter->port($code));
    }
}
