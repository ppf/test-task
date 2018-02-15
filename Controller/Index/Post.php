<?php
/**
 * Created by PhpStorm.
 * User: piotrfrancuz
 * Date: 14/02/2018
 * Time: 14:51
 */

namespace PPF\CurrencyConverter\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use PPF\CurrencyConverter\API\Converter;

/**
 * Class Post
 * @package PPF\CurrencyConverter\Controller\Index
 */
class Post extends \Magento\Framework\App\Action\Action
{

    CONST RESULT_CURRENCY = "PLN";
    CONST DEFAULT_PRECISION = 3;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var Converter
     */
    protected $converterApi;

    /**
     * Post constructor.
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param Converter $converterApi
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        Converter $converterApi
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->converterApi = $converterApi;

        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $result = $this->resultJsonFactory->create();

        $returnArray = ['success' => 0];

        // response only for ajax requests
        if ($this->getRequest()->isAjax()) {

            $fromCurrency = $this->getRequest()->getParam('from_currency');
            $amount = $this->getRequest()->getParam('amount');

            if ($fromCurrency && (float)$amount) {

                try {

                    // get converted currency amount from API
                    $value = $this->converterApi->convertCurrency($amount, $fromCurrency, self::RESULT_CURRENCY, self::DEFAULT_PRECISION);

                    $returnArray['success'] = 1;
                    $returnArray['value'] = $value;
                    $returnArray['currency'] = self::RESULT_CURRENCY;

                } catch (\Exception $e) {
                    $returnArray['message'] = $e->getMessage();
                }

            } else {
                $returnArray['message'] = __('Input valid data');
            }

        }

        return $result->setData($returnArray);
    }
}