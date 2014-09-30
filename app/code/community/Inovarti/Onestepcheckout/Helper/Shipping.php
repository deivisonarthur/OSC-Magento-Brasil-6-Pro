<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Onestepcheckout
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Onestepcheckout_Helper_Shipping extends Mage_Core_Helper_Data {

    /**
     * set shipping method for first load checkout page
     */
    public function initShippingMethod() {
        if (!$this->getQuote()->getShippingAddress()->getShippingMethod()) {
            $shippingRates = $this->getShippingRates();
            if ((count($shippingRates) == 1)) {
                $currentShippingRate = current($shippingRates);
                if (count($currentShippingRate) == 1) {
                    $shippingRate = current($currentShippingRate);
                    $shippingMethod = $shippingRate->getCode();
                }
            } elseif ($lastShippingMethod = $this->_getLastShippingMethod()) {
                $shippingMethod = $lastShippingMethod;
            } elseif ($defaultShippingMethod = Mage::helper('onestepcheckout/config')->getDefaultShippingMethod()) {
                $shippingMethod = $defaultShippingMethod;
            }
            if (isset($shippingMethod)) {
                $this->getOnepage()->saveShippingMethod($shippingMethod);
            }
        }
    }

    public function getShippingRates() {
        $address = Mage::getSingleton('checkout/session')
                ->getQuote()
                ->getShippingAddress()
                ->collectShippingRates()
                ->save()
        ;
        return $address->getGroupedAllShippingRates();
        ;
    }

    protected function _getLastShippingMethod() {
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        if (!$customer->getId()) {
            return false;
        }
        $orderCollection = Mage::getResourceModel('sales/order_collection')
                ->addFilter('customer_id', $customer->getId())
                ->addFieldToFilter('shipping_method', array('neq' => ''))
                ->addAttributeToSort('created_at', Varien_Data_Collection_Db::SORT_ORDER_DESC)
                ->setPageSize(1);

        $lastOrder = $orderCollection->getFirstItem();
        if (!$lastOrder->getId()) {
            return false;
        }
        return $lastOrder->getShippingMethod();
    }

    /**
     * @return Mage_Checkout_Model_Type_Onepage
     */
    public function getOnepage() {
        return Mage::getSingleton('checkout/type_onepage');
    }

    public function getQuote() {
        return Mage::getSingleton('checkout/session')->getQuote();
    }

}
