<?php
/**
 *
 * @category   Inovarti
 * @package    Inovarti_Onestepcheckout
 * @author     Suporte <suporte@inovarti.com.br>
 */


class Inovarti_Onestepcheckout_Block_Onestep extends Mage_Checkout_Block_Onepage_Abstract
{
    public function getGrandTotal()
    {
        return Mage::helper('onestepcheckout')->getGrandTotal($this->getQuote());
    }

    public function getPlaceOrderUrl()
    {
        return Mage::getUrl('onestepcheckout/ajax/placeOrder', array('_secure'=>true));
    }

    public function getBlockMap()
    {
        $updater = Mage::getModel('onestepcheckout/updater');
        $result = array();
        foreach($updater->getMap() as $action => $blocks) {
            $result[$action] = array_keys($blocks);
        }
        return $result;
    }

    public function getBlockNumber($isIncrementNeeded = true)
    {
        return Mage::helper('onestepcheckout')->getBlockNumber($isIncrementNeeded);
    }
}