<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Onestepcheckout
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Onestepcheckout_Helper_Newsletter extends Mage_Core_Helper_Data {

    public function isMageNewsletterEnabled() {
        return $this->isModuleOutputEnabled('Mage_Newsletter');
    }

    public function subscribeCustomer($data = array()) {
        Mage::getModel('newsletter/subscriber')->subscribe($data['email']);
    }

}
