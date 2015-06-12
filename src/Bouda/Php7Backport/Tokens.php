<?php

namespace Bouda\Php7Backport;


class Tokens
{
    private $tokens;


    public function __construct (array $tokens)
    {
        $this->tokens = $tokens;
    }


    public function findNextToken(&$currentPosition, $token)
    {
        $offset = 0;

        $currentToken = $this->tokens[$currentPosition];

        while (!$this->isTokenEqual($currentToken, $token))
        {
            $offset += $this->getTokenLength($currentToken);
            
            $currentPosition++;

            $currentToken = $this->tokens[$currentPosition];
        }
        

        return $offset;
    }


    public function goBackIfToken(&$currentPosition, $token)
    {
        $currentPosition--;
        $currentToken = $this->tokens[$currentPosition];
        
        if ($this->isTokenEqual($currentToken, $token))
        {
            return $this->getTokenLength($currentToken);
        }
    }


    private function getTokenLength($token)
    {
        return is_array($token) ? strlen($token[1]) : strlen($token);
    }


    private function isTokenEqual($token, $value)
    {
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
