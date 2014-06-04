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
 * Run in a custom namespace, so the class can be replaced
 */
namespace Clickpress\Shopware;


/**
 * Class ModuleShopwareArticles
 *
 * @copyright  CLICKPRESS Internetagentur
 * @author     Stefan Schulz-Lauterbach <ssl@clickpress.de>
 * @package    Controller
 */
class ModuleShopwareArticles extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_shopware_articles';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### SHOPWARE ARTICLES ###';
			$objTemplate->title = $this->name;

			$objTemplate->id = $this->id;
			$objTemplate->link = $this->title;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}

	/**
	 * Generate module
	 */
	protected function compile()
	{
		//$start = microtime(TRUE);
		$client = new ShopwareApiClient($this->sw_url . '/api',$this->sw_apiuser,$this->sw_apikey);
		//$time = microtime(TRUE) - $start;
		//echo('<br>API Connect: '.$time);

		// Get all articles and filter
		//$start = microtime(TRUE);
		$swarticles = $client->get('articles');
		//$time = microtime(TRUE) - $start;
		//echo('<br>Get all articles: '.$time);

		//$start = microtime(TRUE);
		$articles = $this->prepareSwArticles($swarticles['data']);
		//$time = microtime(TRUE) - $start;
		//echo('<br>Filter (function prepareSwArticles): '.$time);

		// Get details of filtered
		$detailedArticles	=	array();
		foreach ($articles as $article)
		{
			//$start = microtime(TRUE);
			$detail = $client->get('articles/' . $article['id']);
			// $time = microtime(TRUE) - $start;
			//echo('<br>Get details: '.$time);
			$detailedArticles[]	=	$detail['data'];
		}




		// Assign to template
		$this->Template->articles = $detailedArticles;
		$this->Template->shopUrl = $this->sw_url;
	}

	/**
	* Prepare article array
	*/
	protected function prepareSwArticles ($articles)
	{

		$datetime = new \DateTime();
		$now	=	$datetime->format(\DateTime::ISO8601);

		foreach ($articles as $k => $v)
		{
			// Sort out inactive
			if ($v['active'] != 1)	unset($articles[$k]);

			// Sort out unpublished
			if(!empty($v['availableFrom']) && $v['availableFrom'] > $now) {
				unset($articles[$k]);
			}
			if(!empty($v['availableTo']) && $v['availableTo'] < $now) {
				unset($articles[$k]);
			}
		}

		$articles = array_slice($articles, -$this->sw_articlenum, $this->sw_articlenum);
		$articles	=	array_reverse($articles);

		return $articles;
	}
}