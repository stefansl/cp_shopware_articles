<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Cp_shopware_articles
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'Clickpress',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Modules
	'Clickpress\Shopware\ModuleShopwareArticles' => 'system/modules/cp_shopware_articles/modules/ModuleShopwareArticles.php',

	// Classes
	'Clickpress\Shopware\ShopwareApiClient'      => 'system/modules/cp_shopware_articles/classes/ShopwareApiClient.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_shopware_articles' => 'system/modules/cp_shopware_articles/templates',
));
