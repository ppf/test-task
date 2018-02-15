<?php
/**
 * Created by PhpStorm.
 * User: piotrfrancuz
 * Date: 14/02/2018
 * Time: 15:07
 */

namespace PPF\CurrencyConverter\API;

use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class Converter
 * @package PPF\CurrencyConverter\API
 */
class Converter
{

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Converter constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * convert given amount from one currency to another
     *
     * @param $amount
     * @param $fromCurrency
     * @param $toCurrency
     * @param int $precision
     * @return float
     * @throws \Exception
     */
    public function convertCurrency($amount, $fromCurrency, $toCurrency, $precision = 3)
    {

        // check if module is enabled
        if (!$this->scopeConfig->getValue('currency_converter/general/enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)) {
            throw new \Exception('CurrencyConverter not enabled.');
        }

        $amount = urlencode($amount);
        $from = urlencode($fromCurrency);
        $to = urlencode($toCurrency);

        // check allowed currencies (it will be helpful when converter will have more currencies)
        $allowedCurrencies = $this->scopeConfig->getValue('currency_converter/general/allowed_currency', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        if ($allowedCurrencies && !in_array($from, explode(',', $allowedCurrencies))) {
            throw new \Exception('From currency not allowed.');
        }

        if (!$from) {
            throw new \Exception('From currency not set.');
        }

        if (!$to) {
            throw new \Exception('Result currency not set.');
        }

        // get API url
        $apiUrl = $this->scopeConfig->getValue('currency_converter/api/url', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        // get data from API
        $data = file_get_contents($apiUrl . '?base=' . $from . '&symbols=' . $to);
        $json = json_decode($data);
        $rates = isset($json->rates) ? $json->rates : null;

        if ($rates && isset($rates->$to)) {
            $rate = $rates->$to;
        }

        // check if rate was in API
        if (!isset($rate)) {
            throw new \Exception('Error with API response.');
        }

        // round result to given precision
        return round($amount * $rate, $precision);
    }
}