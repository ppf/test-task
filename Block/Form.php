<?php
/**
 * Created by PhpStorm.
 * User: piotrfrancuz
 * Date: 14/02/2018
 * Time: 14:52
 */

namespace PPF\CurrencyConverter\Block;

/**
 * Class Form
 * @package PPF\CurrencyConverter\Block
 */
class Form extends \Magento\Framework\View\Element\Template
{
    /**
     * get text for form header
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        return __('Converter');
    }

    /**
     * return post url for HTML form
     * @return string
     */
    public function getSubmitUrl()
    {
        return $this->getUrl('currency/index/post');
    }
}