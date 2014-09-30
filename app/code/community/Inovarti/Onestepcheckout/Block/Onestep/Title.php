<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Onestepcheckout
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Onestepcheckout_Block_Onestep_Title extends Mage_Checkout_Block_Onepage_Abstract {

    public function getTitle() {
        $helper = Mage::helper('onestepcheckout/config');
        return $this->escapeHtml($helper->getCheckoutTitle());
    }

    public function getDescription() {
        $helper = Mage::helper('onestepcheckout/config');
        return $helper->getCheckoutDescription();
    }

}
