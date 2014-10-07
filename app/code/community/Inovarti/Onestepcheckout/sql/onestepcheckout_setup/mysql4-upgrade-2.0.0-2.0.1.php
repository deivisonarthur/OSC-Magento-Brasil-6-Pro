<?php

/**
 *
 * @category   Inovarti
 * @package    Inovarti_Onestepcheckout
 * @author     Suporte <suporte@inovarti.com.br>
 */
$installer = $this;
$installer->startSetup();

$prefix = Mage::getConfig()->getTablePrefix();

$collection = Mage::getModel('directory/region')->getResourceCollection()
        ->addCountryCodeFilter('BR')
        ->load();

if (count($collection) == 0) {
    $installer->run("
    INSERT INTO `" . $prefix . "directory_country_region` (`country_id`, `code`, `default_name`) SELECT * FROM (SELECT 'BR', 'AC', 'Acre') AS tmp WHERE NOT EXISTS (SELECT `default_name` FROM `" . $prefix . "directory_country_region` WHERE `default_name`='Acre') LIMIT 1;
    INSERT INTO `" . $prefix . "directory_country_region_name` (`locale`, `region_id`, `name`) SELECT * FROM (SELECT 'pt_BR', LAST_INSERT_ID(), 'Acre') AS tmp WHERE NOT EXISTS (SELECT `name` FROM `" . $prefix . "directory_country_region_name` WHERE `name`='Acre') LIMIT 1;

    INSERT INTO `" . $prefix . "directory_country_region` (`country_id`, `code`, `default_name`) SELECT * FROM (SELECT 'BR', 'AL', 'Alagoas') AS tmp WHERE NOT EXISTS (SELECT `default_name` FROM `" . $prefix . "directory_country_region` WHERE `default_name`='Alagoas') LIMIT 1;
    INSERT INTO `" . $prefix . "directory_country_region_name` (`locale`, `region_id`, `name`) SELECT * FROM (SELECT 'pt_BR', LAST_INSERT_ID(), 'Alagoas') AS tmp WHERE NOT EXISTS (SELECT `name` FROM `" . $prefix . "directory_country_region_name` WHERE `name`='Alagoas') LIMIT 1;

    INSERT INTO `" . $prefix . "directory_country_region` (`country_id`, `code`, `default_name`) SELECT * FROM (SELECT 'BR', 'AP', 'Amapá') AS tmp WHERE NOT EXISTS (SELECT `default_name` FROM `" . $prefix . "directory_country_region` WHERE `default_name`='Amapá') LIMIT 1;
    INSERT INTO `" . $prefix . "directory_country_region_name` (`locale`, `region_id`, `name`) SELECT * FROM (SELECT 'pt_BR', LAST_INSERT_ID(), 'Amapá') AS tmp WHERE NOT EXISTS (SELECT `name` FROM `" . $prefix . "directory_country_region_name` WHERE `name`='Amapá') LIMIT 1;

    INSERT INTO `" . $prefix . "directory_country_region` (`country_id`, `code`, `default_name`) SELECT * FROM (SELECT 'BR', 'AM', 'Amazonas') AS tmp WHERE NOT EXISTS (SELECT `default_name` FROM `" . $prefix . "directory_country_region` WHERE `default_name`='Amazonas') LIMIT 1;
    INSERT INTO `" . $prefix . "directory_country_region_name` (`locale`, `region_id`, `name`) SELECT * FROM (SELECT 'pt_BR', LAST_INSERT_ID(), 'Amazonas') AS tmp WHERE NOT EXISTS (SELECT `name` FROM `" . $prefix . "directory_country_region_name` WHERE `name`='Amazonas') LIMIT 1;

    INSERT INTO `" . $prefix . "directory_country_region` (`country_id`, `code`, `default_name`) SELECT * FROM (SELECT 'BR', 'BA', 'Bahia') AS tmp WHERE NOT EXISTS (SELECT `default_name` FROM `" . $prefix . "directory_country_region` WHERE `default_name`='Bahia') LIMIT 1;
    INSERT INTO `" . $prefix . "directory_country_region_name` (`locale`, `region_id`, `name`) SELECT * FROM (SELECT 'pt_BR', LAST_INSERT_ID(), 'Bahia') AS tmp WHERE NOT EXISTS (SELECT `name` FROM `" . $prefix . "directory_country_region_name` WHERE `name`='Bahia') LIMIT 1;

    INSERT INTO `" . $prefix . "directory_country_region` (`country_id`, `code`, `default_name`) SELECT * FROM (SELECT 'BR', 'CE', 'Ceará') AS tmp WHERE NOT EXISTS (SELECT `default_name` FROM `" . $prefix . "directory_country_region` WHERE `default_name`='Ceará') LIMIT 1;
    INSERT INTO `" . $prefix . "directory_country_region_name` (`locale`, `region_id`, `name`) SELECT * FROM (SELECT 'pt_BR', LAST_INSERT_ID(), 'Ceará') AS tmp WHERE NOT EXISTS (SELECT `name` FROM `" . $prefix . "directory_country_region_name` WHERE `name`='Ceará') LIMIT 1;

    INSERT INTO `" . $prefix . "directory_country_region` (`country_id`, `code`, `default_name`) SELECT * FROM (SELECT 'BR', 'ES', 'Espírito Santo') AS tmp WHERE NOT EXISTS (SELECT `default_name` FROM `" . $prefix . "directory_country_region` WHERE `default_name`='Espírito Santo') LIMIT 1;
    INSERT INTO `" . $prefix . "directory_country_region_name` (`locale`, `region_id`, `name`) SELECT * FROM (SELECT 'pt_BR', LAST_INSERT_ID(), 'Espírito Santo') AS tmp WHERE NOT EXISTS (SELECT `name` FROM `" . $prefix . "directory_country_region_name` WHERE `name`='Espírito Santo') LIMIT 1;

    INSERT INTO `" . $prefix . "directory_country_region` (`country_id`, `code`, `default_name`) SELECT * FROM (SELECT 'BR', 'GO', 'Goiás') AS tmp WHERE NOT EXISTS (SELECT `default_name` FROM `" . $prefix . "directory_country_region` WHERE `default_name`='Goiás') LIMIT 1;
    INSERT INTO `" . $prefix . "directory_country_region_name` (`locale`, `region_id`, `name`) SELECT * FROM (SELECT 'pt_BR', LAST_INSERT_ID(), 'Goiás') AS tmp WHERE NOT EXISTS (SELECT `name` FROM `" . $prefix . "directory_country_region_name` WHERE `name`='Goiás') LIMIT 1;

    INSERT INTO `" . $prefix . "directory_country_region` (`country_id`, `code`, `default_name`) SELECT * FROM (SELECT 'BR', 'MA', 'Maranhão') AS tmp WHERE NOT EXISTS (SELECT `default_name` FROM `" . $prefix . "directory_country_region` WHERE `default_name`='Maranhão') LIMIT 1;
    INSERT INTO `" . $prefix . "directory_country_region_name` (`locale`, `region_id`, `name`) SELECT * FROM (SELECT 'pt_BR', LAST_INSERT_ID(), 'Maranhão') AS tmp WHERE NOT EXISTS (SELECT `name` FROM `" . $prefix . "directory_country_region_name` WHERE `name`='Maranhão') LIMIT 1;

    INSERT INTO `" . $prefix . "directory_country_region` (`country_id`, `code`, `default_name`) SELECT * FROM (SELECT 'BR', 'MT', 'Mato Grosso') AS tmp WHERE NOT EXISTS (SELECT `default_name` FROM `" . $prefix . "directory_country_region` WHERE `default_name`='Mato Grosso') LIMIT 1;
    INSERT INTO `" . $prefix . "directory_country_region_name` (`locale`, `region_id`, `name`) SELECT * FROM (SELECT 'pt_BR', LAST_INSERT_ID(), 'Mato Grosso') AS tmp WHERE NOT EXISTS (SELECT `name` FROM `" . $prefix . "directory_country_region_name` WHERE `name`='Mato Grosso') LIMIT 1;

    INSERT INTO `" . $prefix . "directory_country_region` (`country_id`, `code`, `default_name`) SELECT * FROM (SELECT 'BR', 'MS', 'Mato Grosso do Sul') AS tmp WHERE NOT EXISTS (SELECT `default_name` FROM `" . $prefix . "directory_country_region` WHERE `default_name`='Mato Grosso do Sul') LIMIT 1;
    INSERT INTO `" . $prefix . "directory_country_region_name` (`locale`, `region_id`, `name`) SELECT * FROM (SELECT 'pt_BR', LAST_INSERT_ID(), 'Mato Grosso do Sul') AS tmp WHERE NOT EXISTS (SELECT `name` FROM `" . $prefix . "directory_country_region_name` WHERE `name`='Mato Grosso do Sul') LIMIT 1;

    INSERT INTO `" . $prefix . "directory_country_region` (`country_id`, `code`, `default_name`) SELECT * FROM (SELECT 'BR', 'MG', 'Minas Gerais') AS tmp WHERE NOT EXISTS (SELECT `default_name` FROM `" . $prefix . "directory_country_region` WHERE `default_name`='Minas Gerais') LIMIT 1;
    INSERT INTO `" . $prefix . "directory_country_region_name` (`locale`, `region_id`, `name`) SELECT * FROM (SELECT 'pt_BR', LAST_INSERT_ID(), 'Minas Gerais') AS tmp WHERE NOT EXISTS (SELECT `name` FROM `" . $prefix . "directory_country_region_name` WHERE `name`='Minas Gerais') LIMIT 1;

    INSERT INTO `" . $prefix . "directory_country_region` (`country_id`, `code`, `default_name`) SELECT * FROM (SELECT 'BR', 'PA', 'Pará') AS tmp WHERE NOT EXISTS (SELECT `default_name` FROM `" . $prefix . "directory_country_region` WHERE `default_name`='Pará') LIMIT 1;
    INSERT INTO `" . $prefix . "directory_country_region_name` (`locale`, `region_id`, `name`) SELECT * FROM (SELECT 'pt_BR', LAST_INSERT_ID(), 'Pará') AS tmp WHERE NOT EXISTS (SELECT `name` FROM `" . $prefix . "directory_country_region_name` WHERE `name`='Pará') LIMIT 1;

    INSERT INTO `" . $prefix . "directory_country_region` (`country_id`, `code`, `default_name`) SELECT * FROM (SELECT 'BR', 'PB', 'Paraíba') AS tmp WHERE NOT EXISTS (SELECT `default_name` FROM `" . $prefix . "directory_country_region` WHERE `default_name`='Paraíba') LIMIT 1;
    INSERT INTO `" . $prefix . "directory_country_region_name` (`locale`, `region_id`, `name`) SELECT * FROM (SELECT 'pt_BR', LAST_INSERT_ID(), 'Paraíba') AS tmp WHERE NOT EXISTS (SELECT `name` FROM `" . $prefix . "directory_country_region_name` WHERE `name`='Paraíba') LIMIT 1;

    INSERT INTO `" . $prefix . "directory_country_region` (`country_id`, `code`, `default_name`) SELECT * FROM (SELECT 'BR', 'PR', 'Paraná') AS tmp WHERE NOT EXISTS (SELECT `default_name` FROM `" . $prefix . "directory_country_region` WHERE `default_name`='Paraná') LIMIT 1;
    INSERT INTO `" . $prefix . "directory_country_region_name` (`locale`, `region_id`, `name`) SELECT * FROM (SELECT 'pt_BR', LAST_INSERT_ID(), 'Paraná') AS tmp WHERE NOT EXISTS (SELECT `name` FROM `" . $prefix . "directory_country_region_name` WHERE `name`='Paraná') LIMIT 1;

    INSERT INTO `" . $prefix . "directory_country_region` (`country_id`, `code`, `default_name`) SELECT * FROM (SELECT 'BR', 'PE', 'Pernambuco') AS tmp WHERE NOT EXISTS (SELECT `default_name` FROM `" . $prefix . "directory_country_region` WHERE `default_name`='Pernambuco') LIMIT 1;
    INSERT INTO `" . $prefix . "directory_country_region_name` (`locale`, `region_id`, `name`) SELECT * FROM (SELECT 'pt_BR', LAST_INSERT_ID(), 'Pernambuco') AS tmp WHERE NOT EXISTS (SELECT `name` FROM `" . $prefix . "directory_country_region_name` WHERE `name`='Pernambuco') LIMIT 1;

    INSERT INTO `" . $prefix . "directory_country_region` (`country_id`, `code`, `default_name`) SELECT * FROM (SELECT 'BR', 'PI', 'Piauí') AS tmp WHERE NOT EXISTS (SELECT `default_name` FROM `" . $prefix . "directory_country_region` WHERE `default_name`='Piauí') LIMIT 1;
    INSERT INTO `" . $prefix . "directory_country_region_name` (`locale`, `region_id`, `name`) SELECT * FROM (SELECT 'pt_BR', LAST_INSERT_ID(), 'Piauí') AS tmp WHERE NOT EXISTS (SELECT `name` FROM `" . $prefix . "directory_country_region_name` WHERE `name`='Piauí') LIMIT 1;

    INSERT INTO `" . $prefix . "directory_country_region` (`country_id`, `code`, `default_name`) SELECT * FROM (SELECT 'BR', 'RJ', 'Rio de Janeiro') AS tmp WHERE NOT EXISTS (SELECT `default_name` FROM `" . $prefix . "directory_country_region` WHERE `default_name`='Rio de Janeiro') LIMIT 1;
    INSERT INTO `" . $prefix . "directory_country_region_name` (`locale`, `region_id`, `name`) SELECT * FROM (SELECT 'pt_BR', LAST_INSERT_ID(), 'Rio de Janeiro') AS tmp WHERE NOT EXISTS (SELECT `name` FROM `" . $prefix . "directory_country_region_name` WHERE `name`='Rio de Janeiro') LIMIT 1;

    INSERT INTO `" . $prefix . "directory_country_region` (`country_id`, `code`, `default_name`) SELECT * FROM (SELECT 'BR', 'RN', 'Rio Grande do Norte') AS tmp WHERE NOT EXISTS (SELECT `default_name` FROM `" . $prefix . "directory_country_region` WHERE `default_name`='Rio Grande do Norte') LIMIT 1;
    INSERT INTO `" . $prefix . "directory_country_region_name` (`locale`, `region_id`, `name`) SELECT * FROM (SELECT 'pt_BR', LAST_INSERT_ID(), 'Rio Grande do Norte') AS tmp WHERE NOT EXISTS (SELECT `name` FROM `" . $prefix . "directory_country_region_name` WHERE `name`='Rio Grande do Norte') LIMIT 1;

    INSERT INTO `" . $prefix . "directory_country_region` (`country_id`, `code`, `default_name`) SELECT * FROM (SELECT 'BR', 'RS', 'Rio Grande do Sul') AS tmp WHERE NOT EXISTS (SELECT `default_name` FROM `" . $prefix . "directory_country_region` WHERE `default_name`='Rio Grande do Sul') LIMIT 1;
    INSERT INTO `" . $prefix . "directory_country_region_name` (`locale`, `region_id`, `name`) SELECT * FROM (SELECT 'pt_BR', LAST_INSERT_ID(), 'Rio Grande do Sul') AS tmp WHERE NOT EXISTS (SELECT `name` FROM `" . $prefix . "directory_country_region_name` WHERE `name`='Rio Grande do Sul') LIMIT 1;

    INSERT INTO `" . $prefix . "directory_country_region` (`country_id`, `code`, `default_name`) SELECT * FROM (SELECT 'BR', 'RO', 'Rondônia') AS tmp WHERE NOT EXISTS (SELECT `default_name` FROM `" . $prefix . "directory_country_region` WHERE `default_name`='Rondônia') LIMIT 1;
    INSERT INTO `" . $prefix . "directory_country_region_name` (`locale`, `region_id`, `name`) SELECT * FROM (SELECT 'pt_BR', LAST_INSERT_ID(), 'Rondônia') AS tmp WHERE NOT EXISTS (SELECT `name` FROM `" . $prefix . "directory_country_region_name` WHERE `name`='Rondônia') LIMIT 1;

    INSERT INTO `" . $prefix . "directory_country_region` (`country_id`, `code`, `default_name`) SELECT * FROM (SELECT 'BR', 'RR', 'Roraima') AS tmp WHERE NOT EXISTS (SELECT `default_name` FROM `" . $prefix . "directory_country_region` WHERE `default_name`='Roraima') LIMIT 1;
    INSERT INTO `" . $prefix . "directory_country_region_name` (`locale`, `region_id`, `name`) SELECT * FROM (SELECT 'pt_BR', LAST_INSERT_ID(), 'Roraima') AS tmp WHERE NOT EXISTS (SELECT `name` FROM `" . $prefix . "directory_country_region_name` WHERE `name`='Roraima') LIMIT 1;

    INSERT INTO `" . $prefix . "directory_country_region` (`country_id`, `code`, `default_name`) SELECT * FROM (SELECT 'BR', 'SC', 'Santa Catarina') AS tmp WHERE NOT EXISTS (SELECT `default_name` FROM `" . $prefix . "directory_country_region` WHERE `default_name`='Santa Catarina') LIMIT 1;
    INSERT INTO `" . $prefix . "directory_country_region_name` (`locale`, `region_id`, `name`) SELECT * FROM (SELECT 'pt_BR', LAST_INSERT_ID(), 'Santa Catarina') AS tmp WHERE NOT EXISTS (SELECT `name` FROM `" . $prefix . "directory_country_region_name` WHERE `name`='Santa Catarina') LIMIT 1;

    INSERT INTO `" . $prefix . "directory_country_region` (`country_id`, `code`, `default_name`) SELECT * FROM (SELECT 'BR', 'SP', 'São Paulo') AS tmp WHERE NOT EXISTS (SELECT `default_name` FROM `" . $prefix . "directory_country_region` WHERE `default_name`='São Paulo') LIMIT 1;
    INSERT INTO `" . $prefix . "directory_country_region_name` (`locale`, `region_id`, `name`) SELECT * FROM (SELECT 'pt_BR', LAST_INSERT_ID(), 'São Paulo') AS tmp WHERE NOT EXISTS (SELECT `name` FROM `" . $prefix . "directory_country_region_name` WHERE `name`='São Paulo') LIMIT 1;

    INSERT INTO `" . $prefix . "directory_country_region` (`country_id`, `code`, `default_name`) SELECT * FROM (SELECT 'BR', 'SE', 'Sergipe') AS tmp WHERE NOT EXISTS (SELECT `default_name` FROM `" . $prefix . "directory_country_region` WHERE `default_name`='Sergipe') LIMIT 1;
    INSERT INTO `" . $prefix . "directory_country_region_name` (`locale`, `region_id`, `name`) SELECT * FROM (SELECT 'pt_BR', LAST_INSERT_ID(), 'Sergipe') AS tmp WHERE NOT EXISTS (SELECT `name` FROM `" . $prefix . "directory_country_region_name` WHERE `name`='Sergipe') LIMIT 1;

    INSERT INTO `" . $prefix . "directory_country_region` (`country_id`, `code`, `default_name`) SELECT * FROM (SELECT 'BR', 'TO', 'Tocantins') AS tmp WHERE NOT EXISTS (SELECT `default_name` FROM `" . $prefix . "directory_country_region` WHERE `default_name`='Tocantins') LIMIT 1;
    INSERT INTO `" . $prefix . "directory_country_region_name` (`locale`, `region_id`, `name`) SELECT * FROM (SELECT 'pt_BR', LAST_INSERT_ID(), 'Tocantins') AS tmp WHERE NOT EXISTS (SELECT `name` FROM `" . $prefix . "directory_country_region_name` WHERE `name`='Tocantins') LIMIT 1;

    INSERT INTO `" . $prefix . "directory_country_region` (`country_id`, `code`, `default_name`) SELECT * FROM (SELECT 'BR', 'DF', 'Distrito Federal') AS tmp WHERE NOT EXISTS (SELECT `default_name` FROM `" . $prefix . "directory_country_region` WHERE `default_name`='Distrito Federal') LIMIT 1;
    INSERT INTO `" . $prefix . "directory_country_region_name` (`locale`, `region_id`, `name`) SELECT * FROM (SELECT 'pt_BR', LAST_INSERT_ID(), 'Distrito Federal') AS tmp WHERE NOT EXISTS (SELECT `name` FROM `" . $prefix . "directory_country_region_name` WHERE `name`='Distrito Federal') LIMIT 1;
");
}


$installer->endSetup();
