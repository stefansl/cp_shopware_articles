<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
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
$GLOBALS['TL_DCA']['tl_module']['palettes']['cp_shopware_articles']    = '{title_legend},name,headline,type;{config_legend},sw_url,sw_apiuser,sw_apikey,sw_articlenum,sw_onlyhightlight;{redirect_legend},;{template_legend:hide},sw_template;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

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
$GLOBALS['TL_DCA']['tl_module']['fields']['sw_onlyhightlight'] = array
(
    'label'				=>	&$GLOBALS['TL_LANG']['tl_module']['sw_onlyhightlight'],
    'exclude'			=>	true,
    'inputType'         =>	'checkbox',
    'eval'				=>	array('tl_class' => 'w50'),
    'sql'			    => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['sw_template'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['sw_template'],
    'default'                 => 'com_default',
    'exclude'                 => true,
    'inputType'               => 'select',
    'options_callback'        => array('tl_module_shopware_articles', 'getTemplates'),
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "varchar(32) NOT NULL default ''"
);


/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 * @author Stefan Schulz-Lauterbach <http://clickpress.de>
 */
class tl_module_shopware_articles extends Backend
{

    /**
     * Return all navigation templates as array
     *
     * @return array
     */
    public function getTemplates()
    {
        return $this->getTemplateGroup('mod_shopware');
    }
}
