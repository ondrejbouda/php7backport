<?php

require_once __DIR__ . '/BackporterFunctionalTestAbstract.php';


class IntDivTest extends BackporterFunctionalTestAbstract
{
    public function testAnonymousClass()
    {
        $code = '<?php 
intdiv(10, 3);
';

        $expected = '<?php 
(int) floor(10 / 3);
';
        $this->assertEquals($expected, $this->backporter->port($code));
    }
}
