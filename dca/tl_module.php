<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * PHP version 5
 * @copyright  CLICKPRESS Internetagentur
 * @author     Stefan Schulz-Lauterbach
 * @package    cp_shopware_articles
 * @license    LGPL
 * @filesource
 */

/**
* Add palettes to tl_module
*/
$GLOBALS['TL_DCA']['tl_module']['palettes']['cp_shopware_articles']    = '{title_legend},name,headline,type;{config_legend},sw_url,sw_apiuser,sw_apikey,sw_articlenum;{redirect_legend},;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

$GLOBALS['TL_DCA']['tl_module']['fields']['sw_url'] = array
(
	'label'				=>	&$GLOBALS['TL_LANG']['tl_module']['sw_url'],
	'exclude'			=>	true,
	'inputType'         =>	'text',
	'eval'				=>	array('mandatory' => true, 'rgxp' => 'url', 'tl_class' => 'w50'),
	'sql'               =>  "varchar(100) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['sw_apiuser'] = array
(
	'label'				=>	&$GLOBALS['TL_LANG']['tl_module']['sw_apiuser'],
	'exclude'			=>	true,
	'inputType'         =>	'text',
	'eval'				=>	array('mandatory' => true, 'tl_class' => 'w50'),
	'sql'               =>  "varchar(100) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['sw_apikey'] = array
(
	'label'				=>	&$GLOBALS['TL_LANG']['tl_module']['sw_apikey'],
	'exclude'			=>	true,
	'inputType'         =>	'text',
	'eval'				=>	array('mandatory' => true, 'rgxp' => 'alnum', 'tl_class' => 'w50', 'minlength' => 40, 'maxlength' => 40),
	'sql'               =>  "varchar(100) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['sw_articlenum'] = array
(
	'label'				=>	&$GLOBALS['TL_LANG']['tl_module']['sw_articlenum'],
	'exclude'			=>	true,
	'inputType'         =>	'text',
	'eval'				=>	array('mandatory' => true, 'tl_class' => 'w50'),
	'sql'               =>  "varchar(100) NOT NULL default ''"
);

?>