<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Onestepcheckout
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Onestepcheckout_Helper_Config extends Mage_Core_Helper_Abstract {

    /**
     * "Enable One Step Checkout" from system config
     */
    const GENERAL_IS_ENABLED = 'onestepcheckout/general/is_enabled';

    /**
     * "Always Use Billing Address as a Shipping Address" from system config
     */
    const GENERAL_IS_USE_BILLING_AS_SHIPPING = 'onestepcheckout/general/is_use_billing_as_shipping';

    /**
     * "One Step Checkout Page Title" from system config
     */
    const GENERAL_TITLE = 'onestepcheckout/general/title';

    /**
     * "One Step Checkout Description" from system config
     */
    const GENERAL_DESCRIPTION = 'onestepcheckout/general/description';

    /**
     * "One Step Checkout Description" from system config
     */
    const GENERAL_IS_BLOCK_NUMBERING = 'onestepcheckout/general/is_block_numbering';

    /**
     * "Default Payment Method" from system config
     */
    const GENERAL_DEFAULT_PAYMENT_METHOD = 'onestepcheckout/general/default_payment_method';

    /**
     * "Default Shipping Method" from system config
     */
    const GENERAL_DEFAULT_SHIPPING_METHOD = 'onestepcheckout/general/default_shipping_method';

    /**
     * "Cart editable at checkout" from system config
     */
    const GENERAL_IS_CART_EDITABLE = 'onestepcheckout/general/is_cart_editable';

    /**
     * "Company" from system config
     */
    const EXCLUDE_INCLUDE_IS_COMPANY = 'onestepcheckout/exclude_include_fields/is_company';

    /**
     * "Fax" from system config
     */
    const EXCLUDE_INCLUDE_IS_FAX = 'onestepcheckout/exclude_include_fields/is_fax';

    /**
     * "Discount Code" from system config
     */
    const EXCLUDE_INCLUDE_IS_COUPON = 'onestepcheckout/exclude_include_fields/is_coupon';

    /**
     * "Comments" from system config
     */
    const EXCLUDE_INCLUDE_IS_COMMENTS = 'onestepcheckout/exclude_include_fields/is_comments';

    /**
     * "Newsletter Subscription" from system config
     */
    const EXCLUDE_INCLUDE_IS_NEWSLETTER = 'onestepcheckout/exclude_include_fields/is_newsletter';

    /**
     * Display "Apply Coupon" Button from system config
     */
    const FRONTEND_DISPLAY_APPLY_COUPON_BUTTON = 'onestepcheckout/frontend/display_apply_coupon_button';

    /**
     * @param null $store
     * @return mixed
     */
    public function isEnabled($store = null) {
        $isModuleEnabled = $this->isModuleEnabled();
        $isModuleOutputEnabled = $this->isModuleOutputEnabled();
        return $isModuleOutputEnabled && $isModuleEnabled && Mage::getStoreConfig(self::GENERAL_IS_ENABLED, $store);
    }

    /**
     * @param null $store
     * @return mixed
     */
    public function isUseBillingAsShipping($store = null) {
        return Mage::getStoreConfig(self::GENERAL_IS_USE_BILLING_AS_SHIPPING, $store);
    }

    /**
     * @param null $store
     * @return mixed
     */
    public function getCheckoutTitle($store = null) {
        return Mage::getStoreConfig(self::GENERAL_TITLE, $store);
    }

    /**
     * @param null $store
     * @return mixed
     */
    public function getCheckoutDescription($store = null) {
        return Mage::getStoreConfig(self::GENERAL_DESCRIPTION, $store);
    }

    /**
     * @param null $store
     * @return mixed
     */
    public function isBlockNumbering($store = null) {
        return Mage::getStoreConfig(self::GENERAL_IS_BLOCK_NUMBERING, $store);
    }

    /**
     * @param null $store
     * @return mixed
     */
    public function getDefaultPaymentMethod($store = null) {
        return Mage::getStoreConfig(self::GENERAL_DEFAULT_PAYMENT_METHOD, $store);
    }

    /**
     * @param null $store
     * @return mixed
     */
    public function getDefaultShippingMethod($store = null) {
        return Mage::getStoreConfig(self::GENERAL_DEFAULT_SHIPPING_METHOD, $store);
    }

    /**
     * @param null $store
     * @return mixed
     */
    public function getIsCartEditable($store = null) {
        return Mage::getStoreConfig(self::GENERAL_IS_CART_EDITABLE, $store);
    }

    /**
     * @param null $store
     * @return mixed
     */
    public function isCompany($store = null) {
        return Mage::getStoreConfig(self::EXCLUDE_INCLUDE_IS_COMPANY, $store);
    }

    /**
     * @param null $store
     * @return mixed
     */
    public function isFax($store = null) {
        return Mage::getStoreConfig(self::EXCLUDE_INCLUDE_IS_FAX, $store);
    }

    /**
     * @param null $store
     * @return mixed
     */
    public function isCoupon($store = null) {
        return Mage::getStoreConfig(self::EXCLUDE_INCLUDE_IS_COUPON, $store);
    }

    /**
     * @param null $store
     * @return mixed
     */
    public function isCommments($store = null) {
        return Mage::getStoreConfig(self::EXCLUDE_INCLUDE_IS_COMMENTS, $store);
    }

    /**
     * @param null $store
     * @return mixed
     */
    public function isNewsletter($store = null) {
        return Mage::getStoreConfig(self::EXCLUDE_INCLUDE_IS_NEWSLETTER, $store);
    }

    /**
     * @param null $store
     * @return mixed
     */
    public function isApplyCouponButton($store = null) {
        return Mage::getStoreConfig(self::FRONTEND_DISPLAY_APPLY_COUPON_BUTTON, $store);
    }

}
