<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Onestepcheckout
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Onestepcheckout_Block_Onestep_Form_Review_Newsletter_Simple extends Mage_Core_Block_Template {

    protected $_customer = null;
    protected $_subscriptionObject = null;

    public function canShow() {
        if (!Mage::helper('onestepcheckout/newsletter')->isMageNewsletterEnabled()) {
            return false;
        }
        if ($this->isSubscribed()) {
            return false;
        }
        return true;
    }

    public function getCustomer() {
        if (is_null($this->_customer)) {
            $this->_customer = Mage::getSingleton('customer/session')->getCustomer();
        }
        return $this->_customer;
    }

    public function getSubscriptionObject() {
        if (is_null($this->_subscriptionObject)) {
            $this->_subscriptionObject = Mage::getModel('newsletter/subscriber')->loadByCustomer($this->getCustomer());
        }
        return $this->_subscriptionObject;
    }

    public function isSubscribed() {
        if (!is_null($this->getSubscriptionObject())) {
            return $this->getSubscriptionObject()->isSubscribed();
        }
        return false;
    }

    public function getIsSubscribed() {
        $data = Mage::getSingleton('checkout/session')->getData('onestepcheckout_form_values');
        if (isset($data['is_subscribed'])) {
            return $data['is_subscribed'];
        }
        return false;
    }

    public function getSaveFormValuesUrl() {
        return Mage::getUrl('onestepcheckout/ajax/saveFormValues');
    }

}
