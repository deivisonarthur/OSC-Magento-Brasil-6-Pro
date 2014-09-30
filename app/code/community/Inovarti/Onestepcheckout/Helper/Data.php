<?php
/**
 *
 * @category   Inovarti
 * @package    Inovarti_Onestepcheckout
 * @author     Suporte <suporte@inovarti.com.br>
 */

class Inovarti_Onestepcheckout_Helper_Data extends Mage_Core_Helper_Data
{
    const BLOCK_NUMBER_STORAGE_KEY = 'onestepcheckout-number';

    public function isCustomerMustBeLogged()
    {
        $helper = Mage::helper('checkout');
        if (method_exists($helper, 'isCustomerMustBeLogged')) {
            return $helper->isCustomerMustBeLogged();
        }
        return false;
    }

    /**
     * @param bool $isIncrementNeeded
     *
     * @return int|null
     */
    public function getBlockNumber($isIncrementNeeded = true)
    {
        $configHelper = Mage::helper('onestepcheckout/config');
        if (!$configHelper->isBlockNumbering()) {
            return null;
        }
        $currentNumber = Mage::registry(self::BLOCK_NUMBER_STORAGE_KEY);
        if (is_null($currentNumber)) {
            $currentNumber = 0;
        }
        $currentNumber++;
        if ($isIncrementNeeded) {
            Mage::unregister(self::BLOCK_NUMBER_STORAGE_KEY);
            Mage::register(self::BLOCK_NUMBER_STORAGE_KEY, $currentNumber);
        }
        return $currentNumber;
    }

    public function getGrandTotal($quote)
    {
        $grandTotal = $quote->getGrandTotal();
        return Mage::app()->getStore()->getCurrentCurrency()->format($grandTotal, array(), false);
    }
}