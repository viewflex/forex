<?php

namespace Viewflex\Forex\Providers;


use Viewflex\Forex\ForexException;
use Viewflex\Forex\ForexProviderInterface;

class CurrencyConverterApiFree extends BaseProvider implements ForexProviderInterface
{
    
    /**
     * @param string $source
     * @param string $target
     * @return float
     * @throws ForexException
     */
    public function getRate($source, $target)
    {
        $q = $source . '_' . $target;
        $response = $this->request($this->url . '?q=' . $q . '&compact=ultra');
        $content = json_decode($response, true);

        if (array_key_exists('error', $content))
            throw new ForexException('Exchange rate provider returned an error.');

        if (array_key_exists($q, $content))
            $rate = floatval($content[$q]);
        else
            throw new ForexException('Error retrieving exchange rate.');
        
        return $rate;
    }

}
