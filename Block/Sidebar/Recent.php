<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Block\Sidebar;
/**
 * Blog sidebar categories block
 */
class Recent extends \Magerubik\Blog\Block\Post\PostList\AbstractList
{
    use Widget;
    /**
     * @var string
     */
    protected $_widgetKey = 'recent_posts';
    /**
     * @return $this
     */
    public function _construct()
    {
        $this->setPageSize(
            (int) $this->_scopeConfig->getValue(
                'mrblog/sidebar/'.$this->_widgetKey.'/posts_per_page',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            )
        );
        return parent::_construct();
    }
    /**
     * Retrieve block identities
     * @return array
     */
	public function getIdentities()
    {
        return [\Magento\Cms\Model\Block::CACHE_TAG . '_blog_recent_posts_widget'  ];
    }
	/**
	 * @return
	 */
	public function getMediaFolder() {
		$media_folder = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
		return $media_folder;
	}
	public function getConfig($config)
	{
		return $this->_scopeConfig->getValue('mrblog/general/'.$config, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}
}