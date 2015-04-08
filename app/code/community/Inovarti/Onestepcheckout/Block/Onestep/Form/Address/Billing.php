<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Onestepcheckout
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Onestepcheckout_Block_Onestep_Form_Address_Billing extends Mage_Checkout_Block_Onepage_Billing {

    /**
     * Customer Taxvat Widget block
     *
     * @var Mage_Customer_Block_Widget_Taxvat
     */
    protected $_taxvat;
    protected $_attributeValidationClasses = array(
        'company' => '',
        'fax' => '',
        'telephone' => 'required-entry',
        'region' => '',
        'postcode' => 'required-entry',
        'city' => 'required-entry',
        'street' => 'required-entry',
    );

    public function getAddressesHtmlSelect($type) {
        if ($this->isCustomerLoggedIn()) {
            $options = array();
            foreach ($this->getCustomer()->getAddresses() as $address) {
                $options[] = array(
                    'value' => $address->getId(),
                    'label' => $address->format('oneline')
                );
            }

            $addressDetails = Mage::getSingleton('checkout/session')->getData('onestepcheckout_form_values');
            if (isset($addressDetails[$type . '_address_id'])) {
                if (empty($addressDetails[$type . '_address_id'])) {
                    $addressId = 0;
                } else {
                    $addressId = $addressDetails[$type . '_address_id'];
                }
            } else {
                $addressId = $this->getQuote()->getBillingAddress()->getCustomerAddressId();
            }
            if (empty($addressId) && $addressId !== 0) {
                $address = $this->getCustomer()->getPrimaryBillingAddress();
                if ($address) {
                    $addressId = $address->getId();
                }
            }

            $select = $this->getLayout()->createBlock('core/html_select')
                    ->setName($type . '_address_id')
                    ->setId($type . '-address-select')
                    ->setClass('input-text form-control address-select')
                    ->setValue($addressId)
                    ->setOptions($options);

            $select->addOption('', Mage::helper('checkout')->__('New Address'));

            return $select->getHtml();
        }
        return '';
    }

    public function isVatAttributeVisible() {
        $helper = Mage::helper('customer/address');
        if (method_exists($helper, 'isVatAttributeVisible')) {
            return $helper->isVatAttributeVisible();
        }
        return false;
    }

    /**
     * Check whether taxvat is enabled
     *
     * @return bool
     */
    public function isTaxvatEnabled() {
        return $this->getCustomerWidgetTaxvat()->isEnabled();
    }

    public function getAttributeValidationClass($attributeCode) {
        $helper = Mage::helper('customer/address');
        if (method_exists($helper, 'getAttributeValidationClass')) {
            return $helper->getAttributeValidationClass($attributeCode);
        }
        if (array_key_exists($attributeCode, $this->_attributeValidationClasses)) {
            return $this->_attributeValidationClasses[$attributeCode];
        }
        return '';
    }

    public function getCustomerWidgetName() {
        return $this->getLayout()
                        ->createBlock('customer/widget_name')
                        ->setObject($this->_getObjectForCustomerNameWidget())
                        ->setForceUseCustomerRequiredAttributes(!$this->isCustomerLoggedIn())
                        ->setFieldIdFormat('billing:%s')
                        ->setFieldNameFormat('billing[%s]');
    }

    public function getCustomerWidgetDateOfBirth() {
        return $this->getLayout()
                        ->createBlock('onestepcheckout/widget_dob')
                        ->setDate($this->_getDateForDOBWidget())
                        ->setFieldIdFormat('billing:%s')
                        ->setFieldNameFormat('billing[%s]');
    }

    public function getCustomerWidgetGender() {
        return $this->getLayout()
                        ->createBlock('onestepcheckout/widget_gender')
                        ->setGender($this->getDataFromSession('gender'))
                        ->setFieldIdFormat('billing:%s')
                        ->setFieldNameFormat('billing[%s]');
    }

    public function getCountryHtmlSelect($type) {
        $countryId = $this->getDataFromSession('country_id');
        if (is_null($countryId)) {
            $countryId = Mage::getStoreConfig('general/country/default');
        }
        $select = $this->getLayout()->createBlock('core/html_select')
                ->setName($type . '[country_id]')
                ->setId($type . ':country_id')
                ->setTitle($this->__('Country'))
                ->setClass('input-text form-control validate-select')
                ->setValue($countryId)
                ->setOptions($this->getCountryOptions());
        return $select->getHtml();
    }

    public function getTaxvatHtml() {
        return $this->getCustomerWidgetTaxvat()
                        ->setTaxvat($this->getDataFromSession('taxvat'))
                        ->setFieldIdFormat('billing:%s')
                        ->setFieldNameFormat('billing[%s]')
                        ->toHtml();
    }

    /**
     * Get Customer Taxvat Widget block
     *
     * @return Mage_Customer_Block_Widget_Taxvat
     */
    protected function getCustomerWidgetTaxvat() {
        if (!$this->_taxvat) {
            $this->_taxvat = $this->getLayout()->createBlock('customer/widget_taxvat');
        }
        return $this->_taxvat;
    }

    public function isUseBillingAsShipping() {
        return $this->getConfig()->isUseBillingAsShipping();
    }

    public function getConfig() {
        return Mage::helper('onestepcheckout/config');
    }

    public function getAddressChangedUrl() {
        return Mage::getUrl('onestepcheckout/ajax/saveAddress');
    }

    public function isUseBillingAsShippingChecked() {
        if ($address = $this->getQuote()->getShippingAddress()) {
            return $this->getQuote()->getShippingAddress()->getData('same_as_billing');
        }
        return false;
    }

    public function getDataFromSession($path) {
        $formData = Mage::getSingleton('checkout/session')->getData('onestepcheckout_form_values/billing');
        if (!empty($formData[$path])) {
            return $formData[$path];
        }
        return null;
    }

    public function customerMustBeRegistered() {
        return Mage::getSingleton('customer/session')->isLoggedIn() || Mage::helper('checkout')->isAllowedGuestCheckout($this->getQuote());
    }

    protected function _getObjectForCustomerNameWidget() {
        $formData = Mage::getSingleton('checkout/session')->getData('onestepcheckout_form_values');
        $address = Mage::getModel('sales/quote_address');
        if (isset($formData['billing'])) {
            $address->addData($formData['billing']);
        }
        if ($address->getFirstname() || $address->getLastname()) {
            return $address;
        }
        return $this->getQuote()->getCustomer();
    }

    protected function _getDateForDOBWidget() {
        $formData = Mage::getSingleton('checkout/session')->getData('onestepcheckout_form_values');
        if (isset($formData['billing'])) {
            $billing = $formData['billing'];
            if (!empty($billing['year']) && !empty($billing['month']) && !empty($billing['day'])) {
                $zDate = new Zend_Date(array(
                    'year' => $billing['year'],
                    'month' => $billing['month'],
                    'day' => $billing['day'],
                ));
                return $zDate->toString();
            }
        }
        return '';
    }

}
