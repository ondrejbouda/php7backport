<?php

namespace Bouda\Php7Backport;


class Tokens
{
    private $tokens;

    private $savedPosition;


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
        while (!$this->isCurrentTokenEqual($token))
        {
            if (!$this->next())
            {
                throw new Exception("Token '" . $token . "' could not be found.");
            }
        }
    }


    public function prevIfToken($token)
    {
        $this->savePosition();

        $this->prev();

        if ($this->isCurrentTokenEqual($token))
        {
            return $this->getCurrentTokenLength();
        }
        else
        {
            $this->restorePosition();
        }
    }


    private function getCurrentTokenLength()
    {
        return $this->getTokenLength($this->current());
    }


    private function getTokenLength($token)
    {
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


    public function getStringLengthBetweenPositions($position1, $position2)
    {
        if ($position1 > $position2)
        {
            throw new Exception("Second position must be greater than first.");
        }

        $length = 0;

        $tokensPart = array_slice($this->tokens, $position1, $position2 - $position1 + 1, true);

        foreach ($tokensPart as $token)
        {
            $length += $this->getTokenLength($token);
        }

        return $length;
    }


    private function savePosition()
    {
        $this->savedPosition = $this->position();
    }


    private function restorePosition()
    {
        $this->goto($this->savedPosition);
    }
}
