<?php

require_once('vendor/autoload.php');

use Tracy\Debugger;
use Bouda\Php7Backport\Backporter;
use Bouda\Php7Backport\Printer;


Debugger::enable();

Debugger::$maxLen = 2000;
Debugger::$maxDepth = 10;


$code = '<?php

$util->setLogger(new class("test.log") {
    function __construct($file) {}
    public function log($msg)
    {
        echo $msg;
    }
});

$util->setLogger(new class("test.log") {
    function __construct($file) {}
    public function log($msg)
    {
        echo $msg;
    }
});

';

//       0         1         2         3
//       01234567890123456789012345678901
//$code = '<?php function Bar() : string {}';

//       0         1         2         3
//       01234567890123456789012345678901
//$code = '<?php $a ?? $b;';



$backporter = new Backporter();

$output = $backporter->port($code);

dump($output);
