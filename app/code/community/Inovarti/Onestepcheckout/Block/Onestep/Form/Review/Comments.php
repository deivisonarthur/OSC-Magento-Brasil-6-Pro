<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Onestepcheckout
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Onestepcheckout_Block_Onestep_Form_Review_Comments extends Mage_Checkout_Block_Onepage_Abstract {

    public function canShow() {
        if (!Mage::helper('onestepcheckout/config')->isCommments()) {
            return false;
        }
        return true;
    }

    public function getSaveFormValuesUrl() {
        return Mage::getUrl('onestepcheckout/ajax/saveFormValues');
    }

}
