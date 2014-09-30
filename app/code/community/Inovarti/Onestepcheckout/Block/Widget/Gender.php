<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Onestepcheckout
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Onestepcheckout_Block_Widget_Gender extends Mage_Customer_Block_Widget_Abstract {

    /**
     * Initialize block
     */
    public function _construct() {
        parent::_construct();
        $this->setTemplate('onestepcheckout/customer/widget/gender.phtml');
    }

    /**
     * Check if gender attribute enabled in system
     *
     * @return bool
     */
    public function isEnabled() {
        return (bool) $this->_getAttribute('gender')->getIsVisible();
    }

    /**
     * Check if gender attribute marked as required
     *
     * @return bool
     */
    public function isRequired() {
        return (bool) $this->_getAttribute('gender')->getIsRequired();
    }

    /**
     * Get current customer from session
     *
     * @return Mage_Customer_Model_Customer
     */
    public function getCustomer() {
        return Mage::getSingleton('customer/session')->getCustomer();
    }

}
