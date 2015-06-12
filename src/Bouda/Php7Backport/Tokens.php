<?php

namespace Bouda\Php7Backport;


class Tokens
{
    private $tokens;


    public function __construct (array $tokens)
    {
        $this->tokens = $tokens;

        $this->reset();
    }


    public function reset()
    {
        return reset($this->tokens);
    }


    public function end()
    {
        return end($this->tokens);
    }


    public function goto($position)
    {
        while ($this->position() !== $position) 
        {
            $this->next();
        }

        return $this->current();
    }


    public function position()
    {
        return key($this->tokens);
    }


    public function current()
    {
        return current($this->tokens);
    }


    public function next()
    {
        return next($this->tokens);
    }


    public function prev()
    {
        if ($this->position() === 0)
        {
            // do not change cursor
            return false;
        }

        return prev($this->tokens);
    }


    public function findNextToken($token)
    {
        $offset = 0;

        while (!$this->isCurrentTokenEqual($token))
        {
            $offset += $this->getCurrentTokenLength();

            $this->next();
        }

        return $offset;
    }


    public function goBackIfToken($token)
    {
        $this->prev();

        if ($this->isCurrentTokenEqual($token))
        {
            return $this->getCurrentTokenLength();
        }
        else
        {
            // return to original position
            $this->next();
        }
    }


    private function getCurrentTokenLength()
    {
        $token = $this->current();

        return is_array($token) ? strlen($token[1]) : strlen($token);
    }


    private function isCurrentTokenEqual($value)
    {
        $token = $this->current();

        if (is_numeric($value))
        {
            return is_array($token) && $token[0] === $value;
        }
        else
        {
            return $token === $value;
        }
    }


    public function getOffsetFromCurrent($position)
    {
        $offset = 0;

        if ($this->position() < $position)
        {
            while ($this->position() !== $position)
            {
                $offset -= $this->getCurrentTokenLength();

                $this->next();
            }
        }
        elseif ($this->position() > $position)
        {
            while ($this->position() !== $position)
            {
                $offset += $this->getCurrentTokenLength();

                $this->prev();
            }
        }

        return $offset;
    }
}
