<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Onestepcheckout
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Onestepcheckout_AjaxController extends Mage_Checkout_Controller_Action {

    /**
     * @return Inovarti_Onestepcheckout_AjaxController|Mage_Core_Controller_Front_Action
     */
    public function preDispatch() {
        parent::preDispatch();
        $this->_preDispatchValidateCustomer();

        $checkoutSessionQuote = Mage::getSingleton('checkout/session')->getQuote();
        if ($checkoutSessionQuote->getIsMultiShipping()) {
            $checkoutSessionQuote->setIsMultiShipping(false);
            $checkoutSessionQuote->removeAllAddresses();
        }
        return $this;
    }

    /**
     * action for customer login
     */
    public function customerLoginAction() {
        if ($this->_expireAjax()) {
            return;
        }
        $customerSession = Mage::getSingleton('customer/session');
        $result = array(
            'success' => true,
            'messages' => array()
        );
        if (!$customerSession->isLoggedIn()) {
            $login = $this->getRequest()->getPost('login');
            if (!empty($login['username']) && !empty($login['password'])) {
                try {
                    $customerSession->login($login['username'], $login['password']);
                } catch (Mage_Core_Exception $e) {
                    switch ($e->getCode()) {
                        case Mage_Customer_Model_Customer::EXCEPTION_EMAIL_NOT_CONFIRMED:
                            $value = Mage::helper('customer')->getEmailConfirmationUrl($login['username']);
                            $message = $this->__('This account is not confirmed. <a href="%s">Click here</a> to resend confirmation email.', $value);
                            break;
                        case Mage_Customer_Model_Customer::EXCEPTION_INVALID_EMAIL_OR_PASSWORD:
                            $message = $e->getMessage();
                            break;
                        default:
                            $message = $e->getMessage();
                    }
                    $result['success'] = false;
                    $result['messages'][] = $message;
                    $customerSession->setUsername($login['username']);
                } catch (Exception $e) {
                    $result['success'] = false;
                    $result['messages'][] = $this->__("Oops something's wrong");
                    //TODO: think about redirect to login page
                }
            } else {
                $result['success'] = false;
                $result['messages'][] = $this->__('Login and password are required.');
            }
        }  elseif ($customerSession->isLoggedIn()) {
            $this->_redirect(Mage::helper('checkout/url')->getCheckoutUrl());
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * action for customer forgot password
     */
    public function customerForgotPasswordAction() {
        if ($this->_expireAjax()) {
            return;
        }
        $customerSession = Mage::getSingleton('customer/session');
        $result = array(
            'success' => true,
            'messages' => array()
        );
        $email = (string) $this->getRequest()->getPost('email');
        if ($email) {
            if (Zend_Validate::is($email, 'EmailAddress')) {
                /** @var $customer Mage_Customer_Model_Customer */
                $customer = Mage::getModel('customer/customer')
                        ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                        ->loadByEmail($email);
                if ($customer->getId()) {
                    try {
                        Mage::helper('onestepcheckout/customer')->sendForgotPasswordForCustomer($customer);
                    } catch (Exception $exception) {
                        $result['success'] = false;
                        $result['messages'][] = $exception->getMessage();
                    }
                }
            } else {
                $customerSession->setForgottenEmail($email);
                $result['success'] = false;
                $result['messages'][] = $this->__('Invalid email address.');
            }
        } else {
            $result['success'] = false;
            $result['messages'][] = $this->__('Please enter your email.');
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    public function saveFormValuesAction() {
        if ($this->_expireAjax()) {
            return;
        }
        $result = array(
            'success' => true,
            'messages' => array(),
        );
        if ($this->getRequest()->isPost()) {
            $newData = $this->getRequest()->getPost();
            $currentData = Mage::getSingleton('checkout/session')->getData('onestepcheckout_form_values');
            if (!is_array($currentData)) {
                $currentData = array();
            }
            Mage::getSingleton('checkout/session')->setData(
                    'onestepcheckout_form_values', array_merge($currentData, $newData)
            );
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * save checkout billing address
     */
    public function saveAddressAction() {
        if ($this->_expireAjax()) {
            return;
        }
        $result = array(
            'success' => true,
            'messages' => array(),
            'blocks' => array(),
            'grand_total' => ""
        );
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('billing', array());
            $customerAddressId = $this->getRequest()->getPost('billing_address_id', false);

            if (isset($data['email'])) {
                $data['email'] = trim($data['email']);
            }
            $saveBillingResult = Mage::helper('onestepcheckout/address')->saveBilling($data, $customerAddressId);
            $usingCase = isset($data['use_for_shipping']) ? (int) $data['use_for_shipping'] : 0;
            if ($usingCase === 0) {
                $data = $this->getRequest()->getPost('shipping', array());
                $customerAddressId = $this->getRequest()->getPost('shipping_address_id', false);
                $saveShippingResult = Mage::helper('onestepcheckout/address')->saveShipping($data, $customerAddressId);
            }
            if (isset($saveShippingResult)) {
                $saveResult = array_merge($saveBillingResult, $saveShippingResult);
            } else {
                $saveResult = $saveBillingResult;
            }

            if (isset($saveResult['error'])) {
                $result['success'] = false;
                if (is_array($saveResult['message'])) {
                    $result['messages'] = array_merge($result['messages'], $saveResult['message']);
                } else {
                    $result['messages'][] = $saveResult['message'];
                }
            }
            $this->getOnepage()->getQuote()->collectTotals()->save();
            $result['blocks'] = $this->getUpdater()->getBlocks();
            $result['grand_total'] = Mage::helper('onestepcheckout')->getGrandTotal($this->getOnepage()->getQuote());
        } else {
            $result['success'] = false;
            $result['messages'][] = $this->__('Please specify billing address information.');
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * Shipping method save
     */
    public function saveShippingMethodAction() {
        if ($this->_expireAjax()) {
            return;
        }
        $result = array(
            'success' => true,
            'messages' => array(),
            'blocks' => array(),
            'grand_total' => ""
        );
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('shipping_method', '');
            $saveResult = $this->getOnepage()->saveShippingMethod($data);
            if (!isset($saveResult['error'])) {
                // TODO: check is needed?
                Mage::dispatchEvent(
                        'checkout_controller_onepage_save_shipping_method', array(
                    'request' => $this->getRequest(),
                    'quote' => $this->getOnepage()->getQuote()
                        )
                );
            } else {
                $result['success'] = false;
                $result['messages'][] = $saveResult['message'];
            }
            $this->getOnepage()->getQuote()->collectTotals()->save();
            $result['blocks'] = $this->getUpdater()->getBlocks();
            $result['grand_total'] = Mage::helper('onestepcheckout')->getGrandTotal($this->getOnepage()->getQuote());
        } else {
            $result['success'] = false;
            $result['messages'][] = $this->__('Please specify shipping method.');
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * Payment method save
     */
    public function savePaymentMethodAction() {
        if ($this->_expireAjax()) {
            return;
        }
        $result = array(
            'success' => true,
            'messages' => array(),
            'blocks' => array(),
            'grand_total' => ""
        );
        try {
            if ($this->getRequest()->isPost()) {
                $data = $this->getRequest()->getPost('payment', array());

                $session = Mage::getSingleton('checkout/session');
                $saveResult = $this->getOnepage()->savePayment($data);
                if (isset($saveResult['error'])) {
                    $result['success'] = false;
                    $result['messages'][] = $saveResult['message'];
                }
                $this->getOnepage()->getQuote()->collectTotals()->save();
                $result['blocks'] = $this->getUpdater()->getBlocks();
                $result['grand_total'] = Mage::helper('onestepcheckout')->getGrandTotal($this->getOnepage()->getQuote());
            } else {
                $result['success'] = false;
                $result['messages'][] = $this->__('Please specify payment method.');
            }
        } catch (Exception $e) {
            Mage::logException($e);
            $result['success'] = false;
            $result['error'][] = $this->__('Unable to set Payment Method.');
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    public function applyCouponAction() {
        if ($this->_expireAjax()) {
            return;
        }
        $result = array(
            'success' => true,
            'coupon_applied' => false,
            'messages' => array(),
            'blocks' => array(),
            'grand_total' => ""
        );
        if (!$this->getOnepage()->getQuote()->getItemsCount()) {
            $result['success'] = false;
        } else {
            $couponCode = (string) $this->getRequest()->getParam('coupon_code');
            $oldCouponCode = $this->getOnepage()->getQuote()->getCouponCode();
            if (!strlen($couponCode) && !strlen($oldCouponCode)) {
                $result['success'] = false;
            } else {
                try {

                    $this->getOnepage()->getQuote()->getShippingAddress()->setCollectShippingRates(true);
                    $this->getOnepage()->getQuote()->setCouponCode(strlen($couponCode) ? $couponCode : '')
                            ->collectTotals()
                            ->save();
                    if ($couponCode == $this->getOnepage()->getQuote()->getCouponCode()) {
                        $this->getOnepage()->getQuote()->getShippingAddress()->setCollectShippingRates(true);
                        $this->getOnepage()->getQuote()->setTotalsCollectedFlag(false);
                        $this->getOnepage()->getQuote()->collectTotals()->save();
                        //fix for raf
                        Mage::getSingleton('checkout/session')->getMessages(true);
                        if (strlen($couponCode)) {
                            $result['coupon_applied'] = true;
                            $result['messages'][] = $this->__('Coupon code was applied.');
                        } else {
                            $result['coupon_applied'] = false;
                            $result['messages'][] = $this->__('Coupon code was canceled.');
                        }
                    } else {
                        $result['success'] = false;
                        $result['messages'][] = $this->__('Coupon code is not valid.');
                    }
                    $result['blocks'] = $this->getUpdater()->getBlocks();
                    $result['grand_total'] = Mage::helper('onestepcheckout')->getGrandTotal($this->getOnepage()->getQuote());
                } catch (Mage_Core_Exception $e) {
                    $result['success'] = false;
                    $result['messages'][] = $e->getMessage();
                } catch (Exception $e) {
                    $result['success'] = false;
                    $result['messages'][] = $this->__('Cannot apply the coupon code.');
                    Mage::logException($e);
                }
            }
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    public function placeOrderAction() {
        if ($this->_expireAjax()) {
            return;
        }
        $result = array(
            'success' => true,
            'messages' => array(),
        );
        try {
            //TODO: re-factoring. Move to helpers
            if ($this->getRequest()->isPost()) {
                $billingData = $this->getRequest()->getPost('billing', array());
                // save checkout method
                if (!$this->getOnepage()->getCustomerSession()->isLoggedIn()) {
                    if (isset($billingData['create_account'])) {
                        $this->getOnepage()->saveCheckoutMethod(Mage_Checkout_Model_Type_Onepage::METHOD_REGISTER);
                    } else {
                        $this->getOnepage()->saveCheckoutMethod(Mage_Checkout_Model_Type_Onepage::METHOD_GUEST);
                    }
                }

                if (!$this->getOnepage()->getQuote()->getCustomerId() &&
                        Mage_Checkout_Model_Type_Onepage::METHOD_REGISTER == $this->getOnepage()->getQuote()->getCheckoutMethod()
                ) {
                    if ($this->_customerEmailExists($billingData['email'], Mage::app()->getWebsite()->getId())) {
                        $result['success'] = false;
                        $result['messages'][] = $this->__('There is already a customer registered using this email address. Please login using this email address or enter a different email address to register your account.');
                    }
                }

                if ($result['success']) {
                    // save billing address
                    $customerAddressId = $this->getRequest()->getPost('billing_address_id', false);
                    if (isset($billingData['email'])) {
                        $billingData['email'] = trim($billingData['email']);
                    }
                    $saveBillingResult = $this->getOnepage()->saveBilling($billingData, $customerAddressId);

                    //save shipping address
                    if (!isset($billingData['use_for_shipping'])) {
                        $shippingData = $this->getRequest()->getPost('shipping', array());
                        $customerAddressId = $this->getRequest()->getPost('shipping_address_id', false);
                        $saveShippingResult = $this->getOnepage()->saveShipping($shippingData, $customerAddressId);
                    }

                    // check errors
                    if (isset($saveShippingResult)) {
                        $saveResult = array_merge($saveBillingResult, $saveShippingResult);
                    } else {
                        $saveResult = $saveBillingResult;
                    }

                    if (isset($saveResult['error'])) {
                        $result['success'] = false;
                        $result['messages'] = array_merge($result['messages'], $saveResult['message']);
                    } else {
                        // check agreements
                        $requiredAgreements = Mage::helper('checkout')->getRequiredAgreementIds();
                        $postedAgreements = array_keys($this->getRequest()->getPost('inovarti_osc_agreement', array()));
                        if ($diff = array_diff($requiredAgreements, $postedAgreements)) {
                            $result['success'] = false;
                            $result['messages'][] = $this->__('Please agree to all the terms and conditions before placing the order.');
                        } else {
                            if ($data = $this->getRequest()->getPost('payment', false)) {
                                $this->getOnepage()->getQuote()->getPayment()->importData($data);
                            }

                            //save data for use after order save
                            $data = array(
                                'comments' => $this->getRequest()->getPost('comments', false),
                                'is_subscribed' => $this->getRequest()->getPost('is_subscribed', false),
                                'billing' => $this->getRequest()->getPost('billing', array()),
                                'segments_select' => $this->getRequest()->getPost('segments_select', array())
                            );
                            Mage::getSingleton('checkout/session')->setData('onestepcheckout_order_data', $data);

                            $redirectUrl = $this->getOnepage()->getQuote()->getPayment()->getCheckoutRedirectUrl();
                            if (!$redirectUrl) {
                                $this->getOnepage()->saveOrder();
                                $redirectUrl = $this->getOnepage()->getCheckout()->getRedirectUrl();
                            }
                        }
                    }
                }
            } else {
                $result['success'] = false;
            }
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnepage()->getQuote(), $e->getMessage());
            $result['success'] = false;
            $result['messages'][] = $e->getMessage();
        }
        if ($result['success']) {
            $this->getOnepage()->getQuote()->save();
            if (isset($redirectUrl)) {
                $result['redirect'] = $redirectUrl;
            }
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     *
     */
    public function addProductToWishlistAction() {
        if ($this->_expireAjax()) {
            return;
        }
        $result = array(
            'success' => true,
            'messages' => array()
        );
        $customerSession = Mage::getSingleton('customer/session');
        $wishlistSession = Mage::getSingleton('wishlist/session');
        $response = clone $this->getResponse();
        $wishlistControllerInstance = $this->_getCustomerWishlistController($this->getRequest(), $response);
        if (!is_null($wishlistControllerInstance) && method_exists($wishlistControllerInstance, 'addAction')) {
            $wishlistControllerInstance->addAction();
            $wishlistMessagesCollection = $wishlistSession->getMessages(true);
            $customerMessageCollection = $customerSession->getMessages(true);
            $successMessages = array_merge(
                    $wishlistMessagesCollection->getItemsByType(Mage_Core_Model_Message::SUCCESS), $customerMessageCollection->getItemsByType(Mage_Core_Model_Message::SUCCESS)
            );
            if (count($successMessages) === 0) {
                //if something wrong
                $result['success'] = false;
                $product = Mage::getModel('catalog/product')->load($this->getRequest()->getParam('product', 0));
                if (!is_null($product->getId())) {
                    $referer = $product->getUrlModel()->getUrl($product, array());
                    $result['messages'][] = $this->__(
                            'Product "%1$s" has not been added. Please add it <a href="%2$s">from product page</a>', $product->getName(), $referer
                    );
                }
            } else {
                $result['blocks'] = $this->getUpdater()->getBlocks();
                $product = Mage::getModel('catalog/product')->load($this->getRequest()->getParam('product', 0));
                if (!is_null($product->getId())) {
                    $result['messages'][] = $this->__(
                            'Product "%1$s" was successfully added to wishlist', $product->getName()
                    );
                } else {
                    $result['messages'][] = $this->__('Product was successfully added to wishlist');
                }
            }
        } else {
            $result['success'] = false;
            $result['messages'][] = $this->__("Oops something's wrong");
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     *
     */
    public function addProductToCompareListAction() {
        if ($this->_expireAjax()) {
            return;
        }
        $result = array(
            'success' => true,
            'messages' => array()
        );
        $catalogSession = Mage::getSingleton('catalog/session');
        $response = clone $this->getResponse();
        $productCompareControllerInstance = $this->_getProductCompareController($this->getRequest(), $response);
        if (!is_null($productCompareControllerInstance) && method_exists($productCompareControllerInstance, 'addAction')) {
            $productCompareControllerInstance->addAction();
            $messageCollection = $catalogSession->getMessages(true);
            $successMessages = $messageCollection->getItemsByType(Mage_Core_Model_Message::SUCCESS);
            if (count($successMessages) === 0) {
                //if something wrong
                $result['success'] = false;
                $product = Mage::getModel('catalog/product')->load($this->getRequest()->getParam('product', 0));
                if (!is_null($product->getId())) {
                    $referer = $product->getUrlModel()->getUrl($product, array());
                    $result['messages'][] = $this->__(
                            'Product "%1$s" has not been added. Please add it <a href="%2$s">from product page</a>', $product->getName(), $referer
                    );
                }
            } else {
                $result['blocks'] = $this->getUpdater()->getBlocks();
                $product = Mage::getModel('catalog/product')->load($this->getRequest()->getParam('product', 0));
                if (!is_null($product->getId())) {
                    $result['messages'][] = $this->__(
                            'Product "%1$s" was successfully added to compare list', $product->getName()
                    );
                } else {
                    $result['messages'][] = $this->__('Product was successfully added to compare list');
                }
            }
        } else {
            $result['success'] = false;
            $result['messages'][] = $this->__("Oops something's wrong");
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    public function updateBlocksAfterACPAction() {
        if ($this->_expireAjax()) {
            return;
        }
        $result = array(
            'success' => true,
            'messages' => array(),
            'blocks' => $this->getUpdater()->getBlocks(),
            'can_shop' => !$this->getOnepage()->getQuote()->isVirtual(),
            'grand_total' => Mage::helper('onestepcheckout')->getGrandTotal($this->getOnepage()->getQuote())
        );
        switch ($this->getRequest()->getParam('action', 'add')) {
            case 'add':
                $result['messages'][] = $this->__('Product was successfully added to the cart');
                break;
            case 'remove':
                $result['messages'][] = $this->__('Product was successfully remove from the cart');
                break;
            default:
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * @return Mage_Checkout_Model_Type_Onepage
     */
    public function getOnepage() {
        return Mage::getSingleton('checkout/type_onepage');
    }

    /**
     * @return Inovarti_Onestepcheckout_Model_Updater
     */
    public function getUpdater() {
        return Mage::getSingleton('onestepcheckout/updater');
    }

    /**
     * Check can page show for unregistered users
     *
     * @return boolean
     */
    protected function _canShowForUnregisteredUsers() {
        //TODO: show login block only for unregistered
        return Mage::getSingleton('customer/session')->isLoggedIn() || Mage::helper('checkout')->isAllowedGuestCheckout($this->getOnepage()->getQuote()) || !Mage::helper('checkout')->isCustomerMustBeLogged();
    }

    /**
     * @return Inovarti_Onestepcheckout_AjaxController
     */
    protected function _ajaxRedirectResponse() {
        $this->getResponse()
                ->setHeader('HTTP/1.1', '403 Session Expired')
                ->setHeader('Login-Required', 'true')
                ->sendResponse();
        return $this;
    }

    /**
     * @return bool
     */
    protected function _expireAjax() {
        if (!$this->getOnepage()->getQuote()->hasItems() || $this->getOnepage()->getQuote()->getHasError() || $this->getOnepage()->getQuote()->getIsMultiShipping()) {
            $this->_ajaxRedirectResponse();
            return true;
        }
        if (Mage::getSingleton('checkout/session')->getCartWasUpdated(true)) {
            $this->_ajaxRedirectResponse();
            return true;
        }
        return false;
    }

    /**
     * helper
     * @return null|Mage_Core_Controller_Front_Action
     */
    private function _getCustomerWishlistController($request, $response) {
        $fbIntegratorModuleName = 'Mage_Wishlist';
        $controllerName = 'index';

        return $this->_createController($fbIntegratorModuleName, $controllerName, $request, $response);
    }

    /**
     * helper
     * @return null|Mage_Core_Controller_Front_Action
     */
    private function _getProductCompareController($request, $response) {
        $fbIntegratorModuleName = 'Mage_Catalog';
        $controllerName = 'product_compare';

        return $this->_createController($fbIntegratorModuleName, $controllerName, $request, $response);
    }

    /**
     * helper
     * @param $moduleName
     * @param $controllerName
     * @param $request
     * @param $response
     *
     * @return Mage_Core_Controller_Front_Action|null
     */
    private function _createController($moduleName, $controllerName, $request, $response) {
        $router = Mage::app()->getFrontController()->getRouter('standard');
        $controllerFileName = $router->getControllerFileName($moduleName, $controllerName);
        if (!$router->validateControllerFileName($controllerFileName)) {
            return null;
        }
        $controllerClassName = $router->getControllerClassName($moduleName, $controllerName);
        if (!$controllerClassName) {
            return null;
        }

        if (!class_exists($controllerClassName, false)) {
            if (!file_exists($controllerFileName)) {
                return null;
            }
            include $controllerFileName;

            if (!class_exists($controllerClassName, false)) {
                return null;
            }
        }
        $controllerInstance = Mage::getControllerInstance(
                        $controllerClassName, $request, $response
        );
        return $controllerInstance;
    }

    /**
     * Check if customer email exists
     *
     * @param string $email
     * @param int $websiteId
     * @return false|Mage_Customer_Model_Customer
     */
    protected function _customerEmailExists($email, $websiteId = null) {
        $customer = Mage::getModel('customer/customer');
        if ($websiteId) {
            $customer->setWebsiteId($websiteId);
        }
        $customer->loadByEmail($email);
        if ($customer->getId()) {
            return $customer;
        }
        return false;
    }

    private function _isEmailRegistered($email) {
        $model = Mage::getModel('customer/customer');
        $model->setWebsiteId(Mage::app()->getStore()->getWebsiteId())->loadByEmail($email);

        if ($model->getId() == NULL) {
            return false;
        }

        return true;
    }
    
    public function check_emailAction() {
        $validator = new Zend_Validate_EmailAddress();
        $email = $this->getRequest()->getPost('email', false);
        $data = array('result' => 'clean');

        if ($email && $email != '') {
            if (!$validator->isValid($email)) {
                
            } else {
                if ($this->_isEmailRegistered($email)) {
                    $data['result'] = 'exists';
                } else {
                    $data['result'] = 'clean';
                }
            }
        }
        $this->getResponse()->setBody(Zend_Json::encode($data));
    }

    public function check_taxvatAction()
    {

        $taxvat = $this->getRequest()->getParam('taxvat');
        $data['result'] = 'clean';
        if ($taxvat) {
            $storeId = Mage::app()->getStore()->getId();
            $cli = Mage::getResourceModel('customer/customer_collection')
                ->addAttributeToFilter('taxvat', array('eq' => $taxvat))
                ->addAttributeToFilter('store_id', $storeId)
                ->setPageSize(1)
                ->count();
            if ($cli) {
                $data['result'] = 'exists';
            } else {
                $taxvat = preg_replace("/[^0-9]/", "", $taxvat);
                $cli = Mage::getResourceModel('customer/customer_collection')
                    ->addAttributeToFilter('taxvat', array('eq' => $taxvat))
                    ->addAttributeToFilter('store_id', $storeId)
                    ->getSize();
                if ($cli) {
                    $data['result'] = 'exists';
                }
            }
        }
        $this->getResponse()->setBody(Zend_Json::encode($data));
    }

    public function busca_cepAction() {
        if ($this->getRequest()->getPost()) {
            $cep = $this->getRequest()->getPost('cep', false);
        } else {
            $cep = $this->getRequest()->getQuery('cep', false);
        }
        $webservice = 'http://cep.republicavirtual.com.br/web_cep.php';

        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $webservice . '?cep=' . urlencode($cep) . '&formato=javascript');
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);

        $resultado = curl_exec($ch);
        curl_close($ch);

        echo $resultado;
    }

}
