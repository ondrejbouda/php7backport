<?php

namespace BoudaTests;

use Tester\Assert,
	Tester\TestCase,
	Bouda\Php7Backport\Backporter,
	PhpParser;

require_once __DIR__ . '/../bootstrap.php';


class BackporterTest extends TestCase
{
	private $backporter;


	public function setUp()
	{
		$this->backporter = new Backporter(new PhpParser\PrettyPrinter\Standard);
	}


	public function testCoalesceOperator()
	{
		$code = '$foo ?? $bar;';
		$expected = 'isset($foo) && !is_null($foo) ? $foo : $bar;';
		Assert::equal($this->backporter->port($expected), $this->backporter->port($code));
	}


	public function testScalarTypeHint()
	{
		$code = 'function foo(string $x, SomeClass $y) {}';
		$expected = 'function foo($x, SomeClass $y) {}';
		Assert::equal($this->backporter->port($expected), $this->backporter->port($code));
	}


	public function testReturnType()
	{
		$code = 'function foo() : SomeClass {}';
		$expected = 'function foo() {}';
		Assert::equal($this->backporter->port($expected), $this->backporter->port($code));
	}


	public function testSpaceshipOperator()
	{
		$code = '$foo <=> $bar;';
		$expected = '$foo > $bar ? 1 : ($foo < $bar ? -1 : 0);';
		Assert::equal($this->backporter->port($expected), $this->backporter->port($code));
	}
}


$testCase = new BackporterTest;
$testCase->run();
