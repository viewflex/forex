<?php

use Viewflex\Forex\Forex as Server;

class ForexTest extends TestCase
{
    public function testReturnsPositiveFloat()
    {
        $server = new Server();
        $rate = $server->getRate('USD', 'CAD');
        $this->assertNotNull($rate);
        $this->assertGreaterThan(0, $rate);
        $this->assertInternalType('float', $rate);
    }

}
