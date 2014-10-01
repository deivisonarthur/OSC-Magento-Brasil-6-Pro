<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Onestepcheckout
 * @author     Suporte <suporte@inovarti.com.br>
 */
$installer = $this;
$installer->startSetup();

/*Remover atributos tipopessoa do OSC 4*/
if ($this->getAttribute('customer', 'tipopessoa', 'attribute_id')) {
    $this->removeAttribute('customer', 'tipopessoa');
    $this->removeAttribute('customer_address', 'tipopessoa');
}


if (!$this->getAttribute('customer', 'tipopessoa', 'attribute_id')) {
    $installer->addAttribute('customer', 'tipopessoa', array(
        'type' => 'int',
        'input' => 'select',
        'label' => 'Tipo de Pessoa',
        'global' => 1,
        'visible' => 1,
        'required' => 0,
        'user_defined' => 1,
        'sort_order' => 95,
        'visible_on_front' => 1,
        'source' => 'eav/entity_attribute_source_table',
        'option' => array(
            'values' => array('Física', 'Jurídica'),
        ),
    ));
    if (version_compare(Mage::getVersion(), '1.6.0', '<=')) {
        $customer = Mage::getModel('customer/customer');
        $attrSetId = $customer->getResource()->getEntityType()->getDefaultAttributeSetId();
        $installer->addAttributeToSet('customer', $attrSetId, 'General', 'tipopessoa');
    }
    if (version_compare(Mage::getVersion(), '1.4.2', '>=')) {
        Mage::getSingleton('eav/config')
                ->getAttribute('customer', 'tipopessoa')
                ->setData('used_in_forms', array('adminhtml_customer', 'customer_account_create', 'customer_account_edit', 'checkout_register'))
                ->save();
    }
}
if (!$this->getAttribute('customer', 'ie', 'attribute_id')) {

    $installer->addAttribute('customer', 'ie', array(
        'input' => 'text',
        'type' => 'varchar',
        'label' => 'IE (Inscrição Estadual)',
        'visible' => 1,
        'required' => 0,
        'user_defined' => 1,
    ));

    if (version_compare(Mage::getVersion(), '1.6.0', '<=')) {
        $customer = Mage::getModel('customer/customer');
        $attrSetId = $customer->getResource()->getEntityType()->getDefaultAttributeSetId();
        $installer->addAttributeToSet('customer', $attrSetId, 'General', 'ie');
    }
    if (version_compare(Mage::getVersion(), '1.4.2', '>=')) {
        Mage::getSingleton('eav/config')
                ->getAttribute('customer', 'ie')
                ->setData('used_in_forms', array('adminhtml_customer', 'customer_account_create', 'customer_account_edit', 'checkout_register'))
                ->save();
    }
}

$installer->endSetup();
