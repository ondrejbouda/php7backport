<?php

use Bouda\Php7Backport\Tokens;


class TokensTest extends PHPUnit_Framework_TestCase
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
        $this->assertEquals(0, $this->tokens->position());
    }


    public function testReset()
    {
        $this->tokens->end();
        $this->tokens->reset();
        $this->assertEquals(0, $this->tokens->position());
    }


    public function testEnd()
    {
        $this->tokens->end();
        $this->assertEquals(self::T3, $this->tokens->current());
    }


    public function testGotoPosition()
    {
        $this->tokens->gotoPosition(2);
        $this->assertEquals(self::T3, $this->tokens->current());
    }


    public function testPosition()
    {
        $this->assertEquals(0, $this->tokens->position());
    }


    public function testCurrent()
    {
        $this->assertEquals(self::T1, $this->tokens->current());
    }


    public function testNext()
    {
        $this->assertEquals(self::T2, $this->tokens->next());
        $this->tokens->end();
        $this->assertFalse($this->tokens->next());
    }


    public function testPrev()
    {
        $this->assertFalse($this->tokens->prev());
        $this->tokens->next();
        $this->assertEquals(self::T1, $this->tokens->prev());
    }


    public function testFindNextToken()
    {
        $this->tokens->findNextToken(self::T3);
        $this->assertEquals(self::T3, $this->tokens->current());
    }


    /** 
     * @expectedException Bouda\Php7Backport\Exception
     * @expectedExceptionMessage Token 'n' could not be found.
     */
    public function testFindNextTokenException()
    {
        $this->tokens->findNextToken(self::T_NONEXISTENT);
    }


    public function testPrevIfToken()
    {
        $this->tokens->next();
        $this->tokens->prevIfToken(self::T3);
        $this->assertEquals(self::T2, $this->tokens->current());

        $this->tokens->prevIfToken(self::T1);
        $this->assertEquals(self::T1, $this->tokens->current());

        $this->tokens->prevIfToken(self::T1);
        $this->assertEquals(self::T1, $this->tokens->current());
    }


    public function testGetStringLengthBetweenPositions()
    {
        $this->assertEquals(strlen(self::T1) + strlen(self::T2), 
                      $this->tokens->getStringLengthBetweenPositions(0, 1));
    }


    /** 
     * @expectedException Bouda\Php7Backport\Exception
     * @expectedExceptionMessage Second position must be greater than first.
     */
    public function testGetStringLengthBetweenPositionsException()
    {
        $this->tokens->getStringLengthBetweenPositions(1, 0);
    }
}
