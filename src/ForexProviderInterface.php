<?php

namespace Viewflex\Forex;


interface ForexProviderInterface
{

    /**
     * @param string $source
     * @param string $target
     * @return float
     * @throws \Viewflex\Forex\ForexException
     */
    function getRate($source, $target);

    /**
     * Query a URL and get the response.
     *
     * @param $url
     * @return mixed
     */
    function request($url);
    
}
