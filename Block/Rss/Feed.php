<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Block\Rss;
use Magento\Store\Model\ScopeInterface;
/**
 * Blog ree feed block
 */
class Feed extends \Magerubik\Blog\Block\Post\PostList\AbstractList
{
    /**
     * Retrieve rss feed url 
     * @return string
     */
    public function getLink()
    {
        return $this->getUrl('blog/rss/feed');
    }
    /**
     * Retrieve rss feed title 
     * @return string
     */
    public function getTitle()
    {
    	 return $this->_scopeConfig->getValue('mrblog/rss_feed/title', ScopeInterface::SCOPE_STORE);
    }
    /**
     * Retrieve rss feed description 
     * @return string
     */
    public function getDescription()
    {
    	 return $this->_scopeConfig->getValue('mrblog/rss_feed/description', ScopeInterface::SCOPE_STORE);
    }
    /**
     * Retrieve block identities
     * @return array
     */
    public function getIdentities()
    {
        return [\Magento\Cms\Model\Page::CACHE_TAG . '_blog_rss_feed'  ];
    }
}