<?php

require_once('vendor/autoload.php');

use Tracy\Debugger,
	Bouda\Php7Backport\Backporter;


Debugger::enable();

echo "<pre>";


$code = '<?php

function foo(string $x, SomeClass $y) {}

';

//$code = file_get_contents('C:/home/projects/components/src/Bouda/DI/Container.php');


$backporter = new Backporter;

echo $backporter->port($code);
