<?php

namespace BoudaTests;

use Tester\Assert;
use Tester\TestCase;
use Bouda\Php7Backport\Patch;
use Bouda\Php7Backport\Patcher;
use Bouda\Php7Backport\PatchCollection;

require_once __DIR__ . '/../bootstrap.php';


class MockPatch implements Patch
{
    public function getStartPosition($offset = 0) { return 0; }
    public function getOriginalEndPosition($offset = 0) { return 2; }
    public function getOriginalLength() { return 3; }
    public function render() { return ''; }
}

class PatcherTest extends TestCase
{
    private $patcher;

    public function setUp()
    {
        $code = '123456789';

        $this->patcher = new Patcher($code);
    }


    public function testPatcher()
    {
        $patches = new PatchCollection;
        $patches->add(new MockPatch);

        $this->patcher->apply($patches);
        
        Assert::equal('456789', $this->patcher->getCode());
    }
}


$testCase = new PatcherTest;
$testCase->run();
