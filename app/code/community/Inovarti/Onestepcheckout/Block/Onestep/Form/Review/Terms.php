<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Onestepcheckout
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Onestepcheckout_Block_Onestep_Form_Review_Terms extends Mage_Checkout_Block_Agreements {

    public function canShow() {
        if (count($this->getAgreements()) === 0) {
            return false;
        }
        return true;
    }

}
