<?php
/**
 *
 * @category   Inovarti
 * @package    Inovarti_Onestepcheckout
 * @author     Suporte <suporte@inovarti.com.br>
 */


class Inovarti_Onestepcheckout_Block_Onestep_Form_Shippingmethod extends Mage_Checkout_Block_Onepage_Abstract
{
    protected $_rates;
    protected $_address;

    public function getShippingRates()
    {
        if (empty($this->_rates)) {
            $this->getAddress()->collectShippingRates()->save();
            $groups = $this->getAddress()->getGroupedAllShippingRates();
            return $this->_rates = $groups;
        }
        return $this->_rates;
    }

    public function getAddress()
    {
        if (empty($this->_address)) {
            $this->_address = $this->getQuote()->getShippingAddress();
        }
        return $this->_address;
    }

    public function getCarrierName($carrierCode)
    {
        if ($name = Mage::getStoreConfig('carriers/'.$carrierCode.'/title')) {
            return $name;
        }
        return $carrierCode;
    }

    /**
     * Check is Quote items can ship to
     *
     * @return boolean
     */
    public function canShip()
    {
        return !$this->getQuote()->isVirtual();
    }

    public function getAddressShippingMethod()
    {
        return $this->getAddress()->getShippingMethod();
    }

    public function getShippingPrice($price, $flag)
    {
        return $this->getQuote()->getStore()->convertPrice(Mage::helper('tax')->getShippingPrice($price, $flag, $this->getAddress()), true);
    }

    public function getSaveShipmentUrl()
    {
        return Mage::getUrl('onestepcheckout/ajax/saveShippingMethod');
    }

    public function getBlockNumber($isIncrementNeeded = true)
    {
        return Mage::helper('onestepcheckout')->getBlockNumber($isIncrementNeeded);
    }
}