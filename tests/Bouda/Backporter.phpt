<?php

namespace BoudaTests;

use Tester\Assert,
	Tester\TestCase,
	Bouda\Php7Backport\Backporter;

require_once __DIR__ . '/../bootstrap.php';


$backporter = new Backporter;


$code = '$foo ?? $bar;';
$expected = 'isset($foo) && !is_null($foo) ? $foo : $bar;';
Assert::equal($backporter->port($expected), $backporter->port($code));


$code = 'function foo(string $x, SomeClass $y) {}';
$expected = 'function foo($x, SomeClass $y) {}';
Assert::equal($backporter->port($expected), $backporter->port($code));


$code = 'function foo() : SomeClass {}';
$expected = 'function foo() {}';
Assert::equal($backporter->port($expected), $backporter->port($code));


$code = '$foo <=> $bar;';
$expected = '$foo > $bar ? 1 : ($foo < $bar ? -1 : 0);';
Assert::equal($backporter->port($expected), $backporter->port($code));
