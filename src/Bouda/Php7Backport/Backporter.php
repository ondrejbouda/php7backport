<?php

namespace Bouda\Php7Backport;

use PhpParser;


class Backporter
{
	public function __construct()
	{
		$this->traverser = new PhpParser\NodeTraverser;
		$this->traverser->addVisitor(new Visitor);

		$this->parser = new PhpParser\Parser(new PhpParser\Lexer\Emulative);
		$this->printer = new PhpParser\PrettyPrinter\Standard;
	}


	/**
	 * Backports PHP7 code to PHP5.
	 *
	 * @param string $code
	 * @return string ported code
	 * @throws PhpParser\Error
	 */
	public function port($code)
	{
		if (strpos($code, '<?') === false)
		{
			$code = '<?' . $code;
		}

		$parsedStatements = $this->parser->parse($code);

		$portedStatements = $this->traverser->traverse($parsedStatements);

		return $this->printer->prettyPrint($portedStatements);
	}
}
