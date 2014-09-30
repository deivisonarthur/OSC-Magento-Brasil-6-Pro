<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Onestepcheckout
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Onestepcheckout_Block_Onestep_Form_Review_Newsletter extends Mage_Checkout_Block_Onepage_Abstract {

    public function canShow() {
        if (!Mage::helper('onestepcheckout/config')->isNewsletter()) {
            return false;
        }
        return true;
    }

}
