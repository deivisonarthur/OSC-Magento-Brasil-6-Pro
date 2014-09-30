<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Onestepcheckout
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Onestepcheckout_Block_Onestep_Form_Review_Cart extends Mage_Checkout_Block_Onepage_Review_Info {

    public function getUrlToUpdateBlocksAfterACP() {
        return Mage::getUrl('onestepcheckout/ajax/updateBlocksAfterACP', array('_secure' => true));
    }

    public function isCartEditable() {
        return Mage::helper('onestepcheckout/config')->getIsCartEditable();
    }

}
