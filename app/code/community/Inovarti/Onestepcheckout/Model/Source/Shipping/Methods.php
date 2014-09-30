<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Onestepcheckout
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Onestepcheckout_Model_Source_Shipping_Methods {

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray() {
        $shippingMethodsOptionArray = array(
            array(
                'label' => '',
                'value' => '',
            )
        );
        $carrierMethodsList = Mage::getSingleton('shipping/config')->getActiveCarriers();
        ksort($carrierMethodsList);
        foreach ($carrierMethodsList as $carrierMethodCode => $carrierModel) {
            foreach ($carrierModel->getAllowedMethods() as $shippingMethodCode => $shippingMethodTitle) {
                $shippingMethodsOptionArray[] = array(
                    'label' => $this->_getShippingMethodTitle($carrierMethodCode) . ' - ' . $shippingMethodTitle,
                    'value' => $carrierMethodCode . '_' . $shippingMethodCode,
                );
            }
        }
        return $shippingMethodsOptionArray;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray() {
        $shippingMethodsArray = array();
        $carrierMethodsList = Mage::getSingleton('shipping/config')->getActiveCarriers();
        ksort($carrierMethodsList);
        foreach ($carrierMethodsList as $carrierMethodCode => $carrierModel) {
            foreach ($carrierModel->getAllowedMethods() as $shippingMethodCode => $shippingMethodTitle) {
                $shippingCode = $carrierMethodCode . '_' . $shippingMethodCode;
                $shippingTitle = $this->_getShippingMethodTitle($carrierMethodCode) . ' - ' . $shippingMethodTitle;
                $shippingMethodsArray[$shippingCode] = $shippingTitle;
            }
        }
        return $shippingMethodsArray;
    }

    protected function _getShippingMethodTitle($shippingMethodCode) {
        if (!$shippingMethodTitle = Mage::getStoreConfig("carriers/$shippingMethodCode/title")) {
            $shippingMethodTitle = $shippingMethodCode;
        }
        return $shippingMethodTitle;
    }

}
