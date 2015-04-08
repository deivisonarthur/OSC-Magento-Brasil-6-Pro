<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Onestepcheckout
 * @author     Suporte <suporte@inovarti.com.br>
 */
class Inovarti_Onestepcheckout_Block_Onestep_Form_Address_Shipping extends Mage_Checkout_Block_Onepage_Shipping {

    protected $_attributeValidationClasses = array(
        'company' => '',
        'fax' => '',
        'telephone' => 'required-entry',
        'region' => '',
        'postcode' => 'required-entry',
        'city' => 'required-entry',
        'street' => 'required-entry',
    );

    public function isVatAttributeVisible() {
        $helper = Mage::helper('customer/address');
        if (method_exists($helper, 'isVatAttributeVisible')) {
            return $helper->isVatAttributeVisible();
        }
        return false;
    }

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

    public function getCustomerWidgetName() {
        return $this->getLayout()
                        ->createBlock('customer/widget_name')
                        ->setObject($this->_getObjectForCustomerNameWidget())
                        ->setFieldIdFormat('shipping:%s')
                        ->setFieldNameFormat('shipping[%s]')
                        ->setFieldParams('onchange="shipping.setSameAsBilling(false)"')
        ;
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

    public function getDataFromSession($path) {
        $formData = Mage::getSingleton('checkout/session')->getData('onestepcheckout_form_values/shipping');
        if (!empty($formData[$path])) {
            return $formData[$path];
        }
        return null;
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

    public function isUseBillingAsShipping() {
        return $this->getConfig()->isUseBillingAsShipping();
    }

    public function getConfig() {
        return Mage::helper('onestepcheckout/config');
    }

    public function getAddressChangedUrl() {
        return Mage::getUrl('onestepcheckout/ajax/saveAddress');
    }

    protected function _getObjectForCustomerNameWidget() {
        $formData = Mage::getSingleton('checkout/session')->getData('onestepcheckout_form_values');
        $address = Mage::getModel('sales/quote_address');
        if (isset($formData['shipping'])) {
            $address->addData($formData['shipping']);
        }
        if ($address->getFirstname() || $address->getLastname()) {
            return $address;
        }
        return $this->getQuote()->getCustomer();
    }

}
