<?php

namespace Bouda\Php7Backport;

use PhpParser,
	PhpParser\Node\Stmt;


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



	/**
	 * Add double newline at the end Use statement.
	 */
	public function pStmt_Use(Stmt\Use_ $node)
	{
		return parent::pStmt_Use($node) . "\n\n";
	}


	/**
	 * Add newline at the end class method declaration.
	 */
	public function pStmt_ClassMethod(Stmt\ClassMethod $node)
	{
		return parent::pStmt_ClassMethod($node) . "\n";
	}


	/**
	 * Add newline at the end class property declaration.
	 */
	public function pStmt_Property(Stmt\Property $node)
	{
		return parent::pStmt_Property($node) . "\n";
	}
}
