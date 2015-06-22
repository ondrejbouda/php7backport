<?php

require_once __DIR__ . '/BackporterFunctionalTestAbstract.php';


class SpaceshipTest extends BackporterFunctionalTestAbstract
{
    public function testSpaceship()
    {
        $code = '<?php $foo <=> $bar;';
        $expected = '<?php $foo > $bar ? 1 : ($foo < $bar ? -1 : 0);';
        $this->assertEquals($expected, $this->backporter->port($code));
    }
}
