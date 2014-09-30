<?php
/**
 *
 * @category   Inovarti
 * @package    Inovarti_Onestepcheckout
 * @author     Suporte <suporte@inovarti.com.br>
 */


class Inovarti_Onestepcheckout_Block_Onestep_Form_Paymentmethod extends Mage_Checkout_Block_Onepage_Payment_Methods
{
    public function getSavePaymentUrl()
    {
        return Mage::getUrl('onestepcheckout/ajax/savePaymentMethod');
    }

    public function getBlockNumber($isIncrementNeeded = true)
    {
        return Mage::helper('onestepcheckout')->getBlockNumber($isIncrementNeeded);
    }

    public function getMethods()
    {
        $methods = $this->getData('methods');
        if (is_null($methods)) {
            $quote = $this->getQuote();
            $store = $quote ? $quote->getStoreId() : null;
            $methods = $this->helper('payment')->getStoreMethods($store, $quote);
            $total = $quote->getBaseGrandTotal();
            foreach ($methods as $key => $method) {
                if ($this->_canUseMethod($method)
                    && ($total != 0
                        || $method->getCode() == 'free'
                        || ($quote->hasRecurringItems() && $method->canManageRecurringProfiles())
                    )
                ) {
                    $this->_assignMethod($method);
                } else {
                    unset($methods[$key]);
                }
            }
            $this->setData('methods', $methods);
        }
        return $methods;
    }

    public function getEnterpriseRewardHtml()
    {
        if (Mage::helper('core')->isModuleEnabled('Enterprise_Reward')) {
            return Mage::app()->getLayout()
                ->createBlock('enterprise_reward/checkout_payment_additional')
                ->setTemplate('onestepcheckout/onestep/form/payment/rewards.phtml')
                ->toHtml();
        }
        return '';
    }
}