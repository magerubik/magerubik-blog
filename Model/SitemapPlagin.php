<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Model;
use Magento\Sitemap\Model\Sitemap;
/**
 * Blog sitemap plagin
 */
class SitemapPlagin
{
    /**
     * @var \Magento\Sitemap\Model\ResourceModel\Cms\PageFactory
     */
    protected $_postFactory;
    /**
     * Sitemap data
     *
     * @var \Magento\Sitemap\Helper\Data
     */
    protected $_sitemapData;
    /**
     * @var boolean
     */
    protected $_sitemapItemsAdded = false;
    public function __construct(
        \Magento\Sitemap\Helper\Data $sitemapData,
        \Magerubik\Blog\Model\PostFactory $postFactory
    ) {
        $this->_sitemapData = $sitemapData;
        $this->_postFactory = $postFactory;
    }
    /**
     * Before get sitemap items
     * @param  Sitemap $subject
     * @return void
     */
    public function beforeGetSitemapItems(Sitemap $subject)
    {
        if ($this->_sitemapItemsAdded) {
            return;
        }
        $helper = $this->_sitemapData;
        $storeId = $subject->getStoreId();
        $sitemapItem =  new \Magento\Framework\DataObject(
            [
                'changefreq' => 'weekly',
                'priority' => '0.25',
                'collection' => $this->_postFactory->create()->getCollection($storeId),
            ]
        );
        $subject->addSitemapItems($sitemapItem);
        $this->_sitemapItemsAdded = true;
    }
}