<?php


use Tests\TestCase;
use Viewflex\Forex\Forex as Server;

class ForexTest extends TestCase
{
    public function testReturnsPositiveFloat()
    {
        putenv('FOREX_PROVIDER=CurrencyConverterApiFree');
        putenv('FOREX_PROVIDER_URL=http://free.currencyconverterapi.com/api/v6/convert');
        putenv('FOREX_PROVIDER_KEY=');
        putenv('FOREX_CACHE_MINUTES=1');

        $server = new Server();
        $rate = $server->getRate('USD', 'CAD');
        $this->assertNotNull($rate);
        $this->assertGreaterThan(0, $rate);
        $this->assertIsFloat($rate);
    }

}
