<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Onestepcheckout
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Onestepcheckout_Model_Source_Payment_Methods {

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray() {
        $paymentMethodsOptionArray = array(
            array(
                'label' => '',
                'value' => '',
            )
        );
        $paymentMethodsList = Mage::getModel('payment/config')->getActiveMethods();
        ksort($paymentMethodsList);
        foreach ($paymentMethodsList as $paymentMethodCode => $paymentMethod) {
            if ($paymentMethodCode == 'googlecheckout') {
                continue;
            }
            $paymentMethodsOptionArray[] = array(
                'label' => $paymentMethod->getTitle(),
                'value' => $paymentMethodCode,
            );
        }
        return $paymentMethodsOptionArray;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray() {
        $paymentMethodsArray = array();
        $paymentMethodsList = Mage::getModel('payment/config')->getActiveMethods();
        ksort($paymentMethodsList);
        foreach ($paymentMethodsList as $paymentMethodCode => $paymentMethod) {
            $paymentMethodsArray[$paymentMethodCode] = $paymentMethod->getTitle();
        }
        return $paymentMethodsArray;
    }

}
