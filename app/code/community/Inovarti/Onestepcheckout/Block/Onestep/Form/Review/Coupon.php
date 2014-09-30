<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Onestepcheckout
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Onestepcheckout_Block_Onestep_Form_Review_Coupon extends Mage_Checkout_Block_Onepage_Abstract {

    public function canShow() {
        $isAvailable = Mage::helper('onestepcheckout/config')->isCoupon();
        return $isAvailable;
    }

    public function getCouponCode() {
        return $this->getQuote()->getCouponCode();
    }

    public function getApplyCouponAjaxUrl() {
        return Mage::getUrl('onestepcheckout/ajax/applyCoupon', array('_secure' => true));
    }

    public function getCancelCouponAjaxUrl() {
        return Mage::getUrl('onestepcheckout/ajax/cancelCoupon', array('_secure' => true));
    }

    public function getConfig() {
        return Mage::helper('onestepcheckout/config');
    }

}
