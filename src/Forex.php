<?php

namespace Viewflex\Forex;


use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

/**
 * This class provides live exchange rates, optionally cached.
 */
class Forex
{
    /**
     * @var \Viewflex\Forex\ForexProviderInterface;
     */
    protected $service;

    /**
     * The complete list of ISO currency codes.
     *
     * @var array
     */
    protected $currency_codes = [
        'AFN', 'AED', 'ALL', 'AMD', 'ANG', 'AOA', 'ARS', 'AUD', 'AWG', 'AZN', 'BAM', 'BBD', 'BDT',
        'BGN', 'BHD', 'BIF', 'BMD', 'BND', 'BOB', 'BOV', 'BRL', 'BSD', 'BTN', 'BWP', 'BYR', 'BZD',
        'CAD', 'CDF', 'CHE', 'CHF', 'CHW', 'CLF', 'CLP', 'CNY', 'COP', 'COU', 'CRC', 'CUC', 'CUP',
        'CVE', 'CZK', 'DJF', 'DKK', 'DOP', 'DZD', 'EGP', 'ERN', 'ETB', 'EUR', 'FJD', 'FKP', 'GBP',
        'GEL', 'GHS', 'GIP', 'GMD', 'GNF', 'GTQ', 'GYD', 'HKD', 'HNL', 'HRK', 'HTG', 'HUF', 'IDR',
        'ILS', 'INR', 'IQD', 'IRR', 'ISK', 'JMD', 'JOD', 'JPY', 'KES', 'KGS', 'KHR', 'KMF', 'KPW',
        'KRW', 'KWD', 'KYD', 'KZT', 'LAK', 'LBP', 'LKR', 'LRD', 'LSL', 'LYD', 'MAD', 'MDL', 'MGA',
        'MKD', 'MMK', 'MNT', 'MOP', 'MRO', 'MUR', 'MVR', 'MWK', 'MXN', 'MXV', 'MYR', 'MZN', 'NAD',
        'NGN', 'NIO', 'NOK', 'NPR', 'NZD', 'OMR', 'PAB', 'PEN', 'PGK', 'PHP', 'PKR', 'PLN', 'PYG',
        'QAR', 'RON', 'RSD', 'RUB', 'RWF', 'SAR', 'SBD', 'SCR', 'SDG', 'SEK', 'SGD', 'SHP', 'SLL',
        'SOS', 'SRD', 'SSP', 'STD', 'SVC', 'SYP', 'SZL', 'THB', 'TJS', 'TMT', 'TND', 'TOP', 'TRY',
        'TTD', 'TWD', 'TZS', 'UAH', 'UGX', 'USD', 'USN', 'UYI', 'UYU', 'UZS', 'VEF', 'VND', 'VUV',
        'WST', 'XAF', 'XCD', 'XDR', 'XOF', 'XPF', 'XSU', 'XUA', 'YER', 'ZAR', 'ZMW', 'ZWL'
    ];

    /**
     * Gets exchange rate from cache or live source.
     *
     * @param string $source
     * @param string $target
     * @return float
     * @throws ForexException
     */
    public function getRate($source, $target)
    {

        $provider = env('FOREX_PROVIDER');
        $url = env('FOREX_PROVIDER_URL');
        $key = env('FOREX_PROVIDER_KEY', '');
        $cache_minutes = intval(env('FOREX_CACHE_MINUTES', 720));

        if ((! $provider) || (! $url))
            throw new ForexException('Invalid exchange rate provider configuration.');

        if(! in_array($source, $this->currency_codes))
            throw new ForexException('Invalid source currency code.');

        if(! in_array($target, $this->currency_codes))
            throw new ForexException('Invalid target currency code.');

        $conversion = $source.$target;
        
        if (($cache_minutes > 0) && (Cache::has($conversion)))
            $rate = (float) Cache::get($conversion);
        else {

            $provider_class = 'Viewflex\Forex\Providers\\' . $provider;

            if (! class_exists($provider_class))
                throw new ForexException("\"" . $provider . "\" is not a supported provider.");

            $this->service = new $provider_class($url, $key, $cache_minutes);
            $rate = $this->service->getRate($source, $target);
            
            if($rate <= 0)
                throw new ForexException('Error retrieving exchange rate.');

            if ($cache_minutes > 0) {
                if(! Cache::add($conversion, $rate, Carbon::now()->addMinutes($cache_minutes)))
                    throw new ForexException('Unable to add exchange rate to cache.');
            }
            
        }

        return $rate;
    }

}
