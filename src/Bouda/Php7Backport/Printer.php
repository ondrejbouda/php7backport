<?php

namespace Bouda\Php7Backport;

use PhpParser;
use PhpParser\Node\Stmt;


class Printer extends PhpParser\PrettyPrinter\Standard
{
    /**
     * Add newline at the end of file. 
     * Remove trailing whitespace.
     */
    public function prettyPrintFile(array $stmts)
    {
        $output = parent::prettyPrintFile($stmts) . "\n";

        return $this->removeTrailingWhitespace($output);
    }


    protected function removeTrailingWhitespace($input)
    {
        $lines = explode("\n", $input);

        array_walk($lines, function(&$line) {
            $line = rtrim($line);
        });

        return implode("\n", $lines);
    }
}
