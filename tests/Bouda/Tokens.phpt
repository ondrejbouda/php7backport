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
    const T2 = 'bbb';
    const T3 = 'c';
    const T_NONEXISTENT = 'n';


    public function setUp()
    {
        $this->tokens = new Tokens([
            self::T1, self::T2, self::T3
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
        Assert::equal(self::T3, $this->tokens->current());
    }


    public function testGotoPosition()
    {
        $this->tokens->gotoPosition(2);
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


    public function testFindNextToken()
    {
        $this->tokens->findNextToken(self::T3);
        Assert::equal(self::T3, $this->tokens->current());
    }


    /**
     * @throws Bouda\Php7Backport\Exception Token 'n' could not be found.
     */
    public function testFindNextTokenException()
    {
        $this->tokens->findNextToken(self::T_NONEXISTENT);
    }


    public function testPrevIfToken()
    {
        $this->tokens->next();
        $this->tokens->prevIfToken(self::T3);
        Assert::equal(self::T2, $this->tokens->current());

        $this->tokens->prevIfToken(self::T1);
        Assert::equal(self::T1, $this->tokens->current());

        $this->tokens->prevIfToken(self::T1);
        Assert::equal(self::T1, $this->tokens->current());
    }


    public function testGetStringLengthBetweenPositions()
    {
        Assert::equal(strlen(self::T1) + strlen(self::T2), 
                      $this->tokens->getStringLengthBetweenPositions(0, 1));
    }


    /**
     * @throws Bouda\Php7Backport\Exception Second position must be greater than first.
     */
    public function testGetStringLengthBetweenPositionsException()
    {
        $this->tokens->getStringLengthBetweenPositions(1, 0);
    }
}


$testCase = new TokensTest;
$testCase->run();
