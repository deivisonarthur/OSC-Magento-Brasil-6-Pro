<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Onestepcheckout
 * @author     Suporte <suporte@inovarti.com.br>
 */
$installer = $this;
$installer->startSetup();

$installer->getConnection()
    ->addColumn($installer->getTable('sales/order'), 'customer_tipopessoa', 'smallint(1) default null');

$installer->getConnection()
    ->addColumn($installer->getTable('sales/order'), 'customer_ie', 'text default null');
$installer->endSetup();


