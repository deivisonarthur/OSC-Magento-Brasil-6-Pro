<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Onestepcheckout
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Onestepcheckout_Model_Observer {

    public function controllerActionPredispatchCheckout($observer) {
        $controllerInstance = $observer->getControllerAction();
        if (
                $controllerInstance instanceof Mage_Checkout_OnepageController &&
                $controllerInstance->getRequest()->getActionName() !== 'success' &&
                $controllerInstance->getRequest()->getActionName() !== 'failure' &&
                $controllerInstance->getRequest()->getActionName() !== 'saveOrder' &&
                Mage::helper('onestepcheckout/config')->isEnabled()
        ) {
            $controllerInstance->getResponse()->setRedirect(
                    Mage::getUrl('onestepcheckout/index', array('_secure' => true))
            );
            $controllerInstance->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
        }
    }

    /**
     * @param $observer
     * submit order after
     */
    public function checkoutSubmitAllAfter($observer) {
        $oscOrderData = Mage::getSingleton('checkout/session')->getData('onestepcheckout_order_data');
        if (!is_array($oscOrderData)) {
            $oscOrderData = array();
        }

        // subscribe to newsletter
        if (array_key_exists('is_subscribed', $oscOrderData) && $oscOrderData['is_subscribed']) {
            $customer = Mage::getSingleton('customer/session')->getCustomer();
            if ($customer->getId()) {
                $data = array(
                    'email' => $customer->getEmail(),
                    'first_name' => $customer->getFirstname(),
                    'last_name' => $customer->getLastname(),
                    'customer_id' => $customer->getId(),
                );
            } else {
                $billing = $oscOrderData['billing'];
                $data = array(
                    'email' => $billing['email'],
                    'first_name' => $billing['firstname'],
                    'last_name' => $billing['lastname'],
                );
            }
            if (array_key_exists('segments_select', $oscOrderData)) {
                $data['segments_codes'] = $oscOrderData['segments_select'];
            }
            $data['store_id'] = Mage::app()->getStore()->getId();
            Mage::helper('onestepcheckout/newsletter')->subscribeCustomer($data);
        }

        //clear saved values
        Mage::getSingleton('checkout/session')->setData('onestepcheckout_form_values', array());
        Mage::getSingleton('checkout/session')->setData('onestepcheckout_order_data', array());
    }

    /**
     * Compatibility with Paypal Hosted Pro
     * @param $observer
     */
    public function controllerActionPostdispatchOnestepcheckoutAjaxPlaceOrder($observer) {
        $paypalObserver = Mage::getModel('paypal/observer');
        if (!method_exists($paypalObserver, 'setResponseAfterSaveOrder')) {
            return $this;
        }
        $controllerAction = $observer->getEvent()->getControllerAction();
        $result = Mage::helper('core')->jsonDecode(
                $controllerAction->getResponse()->getBody(), Zend_Json::TYPE_ARRAY
        );
        if ($result['success']) {
            $paypalObserver->setResponseAfterSaveOrder($observer);
            $result = Mage::helper('core')->jsonDecode(
                    $controllerAction->getResponse()->getBody(), Zend_Json::TYPE_ARRAY
            );
            $result['is_hosted_pro'] = true;
            $controllerAction->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }

}
