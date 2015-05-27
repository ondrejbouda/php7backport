<?php

namespace BoudaTests;

use Tester\Assert;
use Bouda\Php7Backport\Printer;
use PhpParser;

require_once __DIR__ . '/../bootstrap.php';


$parser = new PhpParser\Parser(new PhpParser\Lexer\Emulative);

$printer = new Printer;

$code = '<?php

namespace Bouda;

use Namespace1, Namespace2;


class x
{
    private $var1;

    private $var2;

    function first()
    {
        return 1;
    }

    function second()
    {
        return 2;
    }

}
';

Assert::same($code, $printer->prettyPrintFile($parser->parse($code)));
