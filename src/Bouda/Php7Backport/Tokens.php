<?php

namespace Bouda\Php7Backport;


class Tokens
{
    private $tokens;


    public function __construct (array $tokens)
    {
        $this->tokens = $tokens;

        $this->resetPosition();
    }


    public function resetPosition()
    {
        reset($this->tokens);
    }


    public function gotoPosition($position)
    {
        while (key($this->tokens) !== $position) 
        {
            next($this->tokens);
        }
    }


    public function findNextToken($token)
    {
        $offset = 0;

        while (!$this->isCurrentTokenEqual($token))
        {
            $offset += $this->getCurrentTokenLength();

            next($this->tokens);
        }

        return $offset;
    }


    public function goBackIfToken($token)
    {
        prev($this->tokens);

        if ($this->isCurrentTokenEqual($token))
        {
            return $this->getCurrentTokenLength();
        }
        else
        {
            // return to original position
            next($this->tokens);
        }
    }


    private function getCurrentTokenLength()
    {
        $token = current($this->tokens);

        return is_array($token) ? strlen($token[1]) : strlen($token);
    }


    private function isCurrentTokenEqual($value)
    {
        $token = current($this->tokens);

        if (is_numeric($value))
        {
            return is_array($token) && $token[0] === $value;
        }
        else
        {
            return $token === $value;
        }
    }
}
