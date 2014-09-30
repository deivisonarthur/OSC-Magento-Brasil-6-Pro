<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Onestepcheckout
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Onestepcheckout_IndexController extends Mage_Checkout_Controller_Action {

    /**
     * @return Inovarti_Onestepcheckout_IndexController
     */
    public function preDispatch() {
        parent::preDispatch();
        $this->_preDispatchValidateCustomer();

        $checkoutSessionQuote = Mage::getSingleton('checkout/session')->getQuote();
        if ($checkoutSessionQuote->getIsMultiShipping()) {
            $checkoutSessionQuote->setIsMultiShipping(false);
            $checkoutSessionQuote->removeAllAddresses();
        }

        if (!$this->_canShowForUnregisteredUsers()) {
            $this->norouteAction();
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
            return;
        }

        return $this;
    }

    public function indexAction() {

        if (Mage::getStoreConfig('onestepcheckout/general/is_authenticate_before')) {
            if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
                $url = Mage::getUrl('onestepcheckout/index/', array('_secure' => true));
                Mage::getSingleton('customer/session')->setBeforeAuthUrl($url);
                $this->_redirectSuccess($url);
                $this->_redirect('customer/account/login/referer/' . Mage::helper('core')->urlEncode($url));
            }
        }

        if (!Mage::helper('onestepcheckout/config')->isEnabled()) {
            Mage::getSingleton('checkout/session')->addError($this->__('The onestep checkout is disabled.'));
            $this->_redirect('checkout/cart');
            return;
        }
        $quote = $this->getOnepage()->getQuote();
        if (!$quote->hasItems() || $quote->getHasError()) {
            $this->_redirect('checkout/cart');
            return;
        }
        if (!$quote->validateMinimumAmount()) {
            $error = Mage::getStoreConfig('sales/minimum_order/error_message');
            Mage::getSingleton('checkout/session')->addError($error);
            $this->_redirect('checkout/cart');
            return;
        }
        Mage::getSingleton('checkout/session')->setCartWasUpdated(false);
        $this->getOnepage()->initCheckout();
        Mage::helper('onestepcheckout/address')->initAddress();
        Mage::helper('onestepcheckout/shipping')->initShippingMethod();
        Mage::helper('onestepcheckout/payment')->initPaymentMethod();
        $this->getOnepage()->getQuote()->setTotalsCollectedFlag(false);
        $this->getOnepage()->getQuote()->collectTotals()->save();
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->getLayout()->getBlock('head')->setTitle($this->__('Checkout'));
        $this->renderLayout();
    }

    public function getOnepage() {
        return Mage::getSingleton('checkout/type_onepage');
    }

    protected function _canShowForUnregisteredUsers() {
        return Mage::getSingleton('customer/session')->isLoggedIn() || $this->getRequest()->getActionName() == 'index' || Mage::helper('checkout')->isAllowedGuestCheckout($this->getOnepage()->getQuote()) || !Mage::helper('onestepcheckout')->isCustomerMustBeLogged();
    }
}
