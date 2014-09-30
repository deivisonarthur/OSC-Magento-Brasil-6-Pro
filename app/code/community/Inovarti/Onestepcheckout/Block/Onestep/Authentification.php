<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Onestepcheckout
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Onestepcheckout_Block_Onestep_Authentification extends Mage_Checkout_Block_Onepage_Abstract {

    public function canShow() {
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            return false;
        }
        return true;
    }

    public function getLoginAjaxAction() {
        return Mage::getUrl('onestepcheckout/ajax/customerLogin', array('_secure' => true));
    }

    public function getForgotPasswordAjaxAction() {
        return Mage::getUrl('onestepcheckout/ajax/customerForgotPassword', array('_secure' => true));
    }

    public function getUsername() {
        $username = Mage::getSingleton('customer/session')->getUsername(true);
        return $this->escapeHtml($username);
    }

}
