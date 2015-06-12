<?php

namespace BoudaTests;

use Tester\Assert;
use Tester\TestCase;
use Bouda\Php7Backport\Tokens;

require_once __DIR__ . '/../bootstrap.php';


class TokensTest extends TestCase
{
    private $tokens;

    const T1 = 'a';
    const T2 = 'b';
    const T3 = 'c';
    const T4 = 'd';
    const T5 = 'e';


    public function setUp()
    {
        $this->tokens = new Tokens([
            self::T1, self::T2, self::T3, self::T4, self::T5
        ]);
    }


    public function testConstruct()
    {
        Assert::equal(0, $this->tokens->position());
    }


    public function testReset()
    {
        $this->tokens->end();
        $this->tokens->reset();
        Assert::equal(0, $this->tokens->position());
    }


    public function testEnd()
    {
        $this->tokens->end();
        Assert::equal(self::T5, $this->tokens->current());
    }


    public function testGoto()
    {
        $this->tokens->goto(2);
        Assert::equal(self::T3, $this->tokens->current());
    }


    public function testPosition()
    {
        Assert::equal(0, $this->tokens->position());
    }


    public function testCurrent()
    {
        Assert::equal(self::T1, $this->tokens->current());
    }


    public function testNext()
    {
        Assert::equal(self::T2, $this->tokens->next());
        $this->tokens->end();
        Assert::false($this->tokens->next());
    }


    public function testPrev()
    {
        Assert::false($this->tokens->prev());
        $this->tokens->next();
        Assert::equal(self::T1, $this->tokens->prev());
    }
}


$testCase = new TokensTest;
$testCase->run();
