<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Onestepcheckout
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Onestepcheckout_Model_Source_Enableddisabled {

    const DISABLED_CODE = 0;
    const ENABLED_CODE = 1;
    const DISABLED_LABEL = 'Disabled';
    const ENABLED_LABEL = 'Enabled';

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray() {
        return array(
            array(
                'value' => self::ENABLED_CODE,
                'label' => Mage::helper('onestepcheckout')->__(self::ENABLED_LABEL),
            ),
            array(
                'value' => self::DISABLED_CODE,
                'label' => Mage::helper('onestepcheckout')->__(self::DISABLED_LABEL),
            ),
        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray() {
        return array(
            self::ENABLED_CODE => Mage::helper('onestepcheckout')->__(self::ENABLED_LABEL),
            self::DISABLED_CODE => Mage::helper('onestepcheckout')->__(self::DISABLED_LABEL),
        );
    }

}
