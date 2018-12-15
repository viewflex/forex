<?php

namespace Viewflex\Forex\Providers;


abstract class BaseProvider
{

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var int
     */
    protected $cache_minutes;


    /**
     * @param string $url
     * @param string $key
     * @param int $cache_minutes
     */
    public function __construct($url, $key, $cache_minutes)
    {
        $this->url = $url;
        $this->key = $key;
        $this->cache_minutes = $cache_minutes;
    }

    /**
     * Query a URL and get the response.
     *
     * @param $url
     * @return mixed
     */
    public function request($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 2);
        curl_setopt($ch, CURLOPT_MAXCONNECTS, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

}
