<?php

use Bouda\Php7Backport\Backporter;


class BackporterFunctionalTestAbstract extends PHPUnit_Framework_TestCase
{
    protected $backporter;


    public function setUp()
    {
        $this->backporter = new Backporter();
    }
}
