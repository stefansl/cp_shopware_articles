<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * PHP version 5
 *
 * @copyright  CLICKPRESS Internetagentur
 * @author     Stefan Schulz-Lauterbach
 * @package    cp_shopware_articles
 * @license    LGPL
 * @filesource
 */


/**
 * Namespace
 */
namespace CLICKPRESS;


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
     *
     * @var string
     */
    protected $strTemplate = 'mod_shopware_articles';
    protected $articleFile = 'system/cache/cp_shopware_articles/article.json';
    /**
     * @var string
     */
    protected $detailArticleFile = 'system/cache/cp_shopware_articles/detailArticle.json';


    /**
     * Display a wildcard in the back end
     *
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE') {
            $objTemplate        = new \BackendTemplate( 'be_wildcard' );

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

        $file                 = new \File( $this->articleFile, true );
        $fileDetail           = new \File( $this->detailArticleFile, true );

        if ($file->exists()) {
            $diff  = time() - $file->__get( 'mtime' );
            $hours = round( $diff / 3600 );
        } else {
            $hours = 7;
        }

        $detailedArticles = array();

        // Load and cache json
        if ($hours > 6 || !$file->exists() || !$fileDetail->exists()) {

            $client = new ShopwareApiClient( $this->sw_url . '/api', $this->sw_apiuser, $this->sw_apikey );

            $swarticles = $client->get( 'articles' );
            $articles   = $this->prepareSwArticles( $swarticles['data'] );

            $jsonArticles = json_encode( $articles );
            $file->write( $jsonArticles );
            $file->close();

            $detailedArticles = $this->getDetailedArticles( $articles, $client, $fileDetail );

            if ($detailedArticles === false) {
                $this->Template->noArticles = $GLOBALS['TL_LANG']['MSC']['no_articles'];
            }

            // Use cached json
        } else {

            $jsonDetail       = $fileDetail->getContent();
            $detailedArticles = json_decode( $jsonDetail, true );

        }


        // Template
        if (($this->sw_template != $this->strTemplate) && ($this->sw_template != '')) {
            $this->strTemplate = $this->sw_template;
            $this->Template   = new \FrontendTemplate( $this->strTemplate );
        }

        if (!empty($detailedArticles)) {

            $detailedArticles = ($this->sw_onlyhightlight == 1) ? $this->filterHighlighted( $detailedArticles ) : $detailedArticles;
            $detailedArticles = ($this->sw_articlenum > 0) ? $this->limitArticles( $detailedArticles ) : $detailedArticles;

            $this->Template->articles = $detailedArticles;
        } else {
            $this->Template->noArticles = $GLOBALS['TL_LANG']['MSC']['no_articles'];
        }

        // Previous and next labels
        $this->Template->previous = $GLOBALS['TL_LANG']['MSC']['previous'];
        $this->Template->next = $GLOBALS['TL_LANG']['MSC']['next'];

        $this->Template->shopUrl = $this->sw_url;
        $this->Template->sw_imgsize = $this->sw_imgsize;
    }


    /**
     * Prepare article array
     *
     * @param $articles
     *
     * @return mixed
     */
    protected function prepareSwArticles( $articles )
    {

        $datetime = new \DateTime();
        $now = $datetime->format( \DateTime::ISO8601 );

        foreach ($articles as $k => $v) {
            // Sort out inactive
            if ($v['active'] != 1) {
                unset($articles[$k]);
            }

            // Sort out unpublished
            if (!empty($v['availableFrom']) && $v['availableFrom'] > $now) {
                unset($articles[$k]);
            }
            if (!empty($v['availableTo']) && $v['availableTo'] < $now) {
                unset($articles[$k]);
            }
        }

        return $articles;
    }

    /**
     * Get detailed article data
     *
     * @param $articles
     * @param $client
     * @param $fileDetail
     *
     * @return array
     */
    protected function getDetailedArticles( $articles, $client, $fileDetail )
    {

        if (!empty($articles)) {
            foreach ($articles as $article) {
                $detail = $client->get( 'articles/' . $article['id'] );
                $detailedArticles[] = $detail['data'];
            }

            $jsonDetail = json_encode( $detailedArticles );

            $fileDetail->write( $jsonDetail );
            $fileDetail->close();

            return $detailedArticles;

        }

        return false;
    }

    /**
     * Sort out last articles
     *
     * @param $articles
     *
     * @return mixed
     */
    protected function filterHighlighted( $articles )
    {

        foreach ($articles as $k => $v) {
            // Sort out not highlighted articles
            if ($v['highlight'] != 1) {
                unset($articles[$k]);
            }
        }

        return $articles;
    }

    /**
     * Limit article collection
     *
     * @param $articles
     *
     * @return array
     */
    protected function limitArticles( $articles )
    {
        if (!empty($articles)) {
            $articles = array_slice( $articles, -$this->sw_articlenum, $this->sw_articlenum );
            $articles = array_reverse( $articles );
        }

        return $articles;
    }


}
