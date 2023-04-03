<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Model;
use Magerubik\Blog\Api\Data\CategoryInterface;
use Magento\Framework\Model\AbstractModel;
class Category extends AbstractModel implements \Magento\Framework\DataObject\IdentityInterface, CategoryInterface
{
    /**
     * Category's Statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
	const PERSISTENT_NAME = 'mrblog_category';
	const CACHE_TAG  = 'mrblog_category';
    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'magerubik_blog_category';
    /**
     * Parameter name in event
     *
     * In observe method you can use $observer->getEvent()->getObject() in this case
     *
     * @var string
     */
    protected $_eventObject = 'blog_category';
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $_url;
    /**
     * Initialize dependencies.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\UrlInterface $url
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_url = $url;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magerubik\Blog\Model\ResourceModel\Category');
    }
	public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
    /**
     * Retrieve model title
     * @param  boolean $plural
     * @return string
     */
    public function getOwnTitle($plural = false)
    {
        return $plural ? 'Category' : 'Categories';
    }
    /**
     * Retrieve true if category is active
     * @return boolean [description]
     */
    public function isActive()
    {
        return ($this->getStatus() == self::STATUS_ENABLED);
    }
    /**
     * Retrieve available category statuses
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_DISABLED => __('Disabled'), self::STATUS_ENABLED => __('Enabled')];
    }
    /**
     * Check if category identifier exist for specific store
     * return category id if category exists
     *
     * @param string $identifier
     * @param int $storeId
     * @return int
     */
    public function checkIdentifier($identifier, $storeId)
    {
        return $this->_getResource()->checkIdentifier($identifier, $storeId);
    }
    /**
     * Retrieve parent category ids
     * @return array
     */
    public function getParentIds()
    {
        $k = 'parent_ids';
        if (!$this->hasData($k)) {
            $this->setData($k,
                $this->getPath() ? explode('/', $this->getPath()) : array()
            );
        }
        return $this->getData($k);
    }
    /**
     * Check if current category is parent category
     * @param  self  $category
     * @return boolean
     */
    public function isParent($category)
    {
        if (is_object($category)) {
            $category = $category->getId();
        }
        return in_array($category, $this->getParentIds());
    }
    /**
     * Check if current category is child category
     * @param  self  $category
     * @return boolean
     */
    public function isChild($category)
    {
        return $category->isParent($this);
    }
    /**
     * Retrieve category depth level
     * @return int
     */
    public function getLevel()
    {
        return count($this->getParentIds());
    }
    /**
     * Retrieve catgegory url route path
     * @return string
     */
    public function getUrl()
    {
        return 'blog/category/'.$this->getIdentifier();
    }
    /**
     * Retrieve category url
     * @return string
     */
    public function getCategoryUrl()
    {
        return $this->_url->getUrl($this->getUrl());
    }
	/**
     * @return int
     */
    public function getCategoryId()
    {
        return (int)$this->_getData(CategoryInterface::CATEGORY_ID);
    }
    /**
     * @param int $categoryId
     */
    public function setCategoryId($categoryId)
    {
        $this->setData(CategoryInterface::CATEGORY_ID, $categoryId);
        return $this;
    }
	/**
     * @return string
     */
    public function getTitle()
    {
        return $this->_getData(CategoryInterface::TITLE);
    }
    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->setData(CategoryInterface::TITLE, $title);
        return $this;
    }
	/**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->_getData(CategoryInterface::URL_KEY);
    }
    /**
     * @param string $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->setData(CategoryInterface::URL_KEY, $identifier);
        return $this;
    }
	/**
     * @return int
     */
    public function getIsActive()
    {
        return $this->_getData(CategoryInterface::STATUS);
    }
    /**
     * @param int $isActive
     */
    public function setIsActive($isActive)
    {
        $this->setData(CategoryInterface::STATUS, $isActive);
        return $this;
    }
	/**
     * @return int
     */
    public function getPosition()
    {
        return (int)$this->_getData(CategoryInterface::POSITION);
    }
    /**
     * @param int $position
     */
    public function setPosition($position)
    {
        $this->setData(CategoryInterface::POSITION, $position);
        return $this;
    }
	/**
     * @return string
     */
    public function getMetaTitle()
    {
        return $this->_getData(CategoryInterface::META_TITLE);
    }
    /**
     * @param null|string $metaTitle
     */
    public function setMetaTitle($metaTitle)
    {
        $this->setData(CategoryInterface::META_TITLE, $metaTitle);
        return $this;
    }
	/**
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->_getData(CategoryInterface::META_KEYWORDS);
    }
    /**
     * @param null|string $metaKeywords
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->setData(CategoryInterface::META_KEYWORDS, $metaKeywords);
        return $this;
    }
	/**
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->_getData(CategoryInterface::META_DESCRIPTION);
    }
    /**
     * @param null|string $metaDescription
     */
    public function setMetaDescription($metaDescription)
    {
        $this->setData(CategoryInterface::META_DESCRIPTION, $metaDescription);
        return $this;
    }
	/**
     * @return int
     */
    public function getStoreId()
    {
        return $this->_getData(CategoryInterface::STORE_ID);
    }
    /**
     * @param int $storeId
     */
    public function setStoreId($storeId)
    {
        $this->setData(CategoryInterface::STORE_ID, $storeId);
        return $this;
    }
	/**
     * @return string
     */
    public function getPath()
    {
        return $this->getData(CategoryInterface::PATH);
    }
    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->setData(CategoryInterface::PATH, $path);
        return $this;
    }
	/**
     * @return int
     */
    public function getParentId()
    {
        return $this->getData(CategoryInterface::PARENT_ID);
    }
	/**
     * @param int $parentId
     */
    public function setParentId($parentId)
    {
        $this->setData(CategoryInterface::PARENT_ID, $parentId);
        return $this;
    }
}