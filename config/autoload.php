<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'CLICKPRESS',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Modules
	'CLICKPRESS\ModuleShopwareArticles' => 'system/modules/cp_shopware_articles/modules/ModuleShopwareArticles.php',

	// Classes
	'CLICKPRESS\ShopwareApiClient'      => 'system/modules/cp_shopware_articles/classes/ShopwareApiClient.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_shopware_articles' => 'system/modules/cp_shopware_articles/templates',
));
