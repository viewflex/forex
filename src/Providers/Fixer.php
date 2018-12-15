<?php

namespace Viewflex\Forex\Providers;


use Viewflex\Forex\ForexException;
use Viewflex\Forex\ForexProviderInterface;

class Fixer extends BaseProvider implements ForexProviderInterface
{
    
    /**
     * @param string $source
     * @param string $target
     * @return float
     * @throws ForexException
     */
    public function getRate($source, $target)
    {
        $response = $this->request($this->url . '?access_key=' . $this->key . '&base=' . $source . '&symbols=' . $target);
        $content = json_decode($response, true);

        if (array_key_exists('error', $content))
            throw new ForexException('Exchange rate provider returned an error.');

        if (
            array_key_exists('rates', $content)
            && array_key_exists($target, $content['rates'])
            && $content['rates'][$target]
        )
            $rate = floatval($content['rates'][$target]);
        else
            throw new ForexException('Error retrieving exchange rate.');
        
        return $rate;
    }

}
