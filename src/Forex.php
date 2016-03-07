<?php namespace Viewflex\Forex;

use Cache;
use Carbon\Carbon;

/**
 * This class provides live exchange rates, optionally cached.
 */
class Forex
{
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
        if(! in_array($source, $this->currency_codes))
            throw new ForexException('Invalid source currency code.');

        if(! in_array($target, $this->currency_codes))
            throw new ForexException('Invalid target currency code.');

        $conversion = $source.$target;

        if (Cache::has($conversion))
            $rate = Cache::get($conversion);
        else {
            $content = trim($this->request('http://download.finance.yahoo.com/d/quotes.csv?s='.$conversion.'=X&f=l1'));

            if($content == 'N/A')
                throw new ForexException('Exchange rate not found.');

            if(($rate = floatval($content)) == 0)
                throw new ForexException('Error retrieving exchange rate.');

            if(! Cache::add($conversion, $rate, Carbon::now()->addMinutes(1440)))
                throw new ForexException('Unable to add exchange rate to cache.');
        }

        return $rate;
    }

    /**
     * Query a URL and get the response.
     *
     * @param $url
     * @return mixed
     */
    private function request($url)
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
