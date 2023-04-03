<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Model;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Magerubik\Blog\Api\Data\PostInterface;
use Magerubik\Blog\Model\ResourceModel\Post as PostResource;
class Post extends AbstractModel implements IdentityInterface, PostInterface
{
    const CACHE_TAG = 'mrblog_post';
	const CURRENT_NAME = 'mrblog_post';
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    protected $_eventPrefix = 'magerubik_blog_post';
    protected $_eventObject = 'blog_post';
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $_url;
    /**
     * @var \Magerubik\Blog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $_categoryCollectionFactory;
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;
    /**
     * @var \Magerubik\Blog\Model\ResourceModel\Category\Collection
     */
    protected $_parentCategories;
    /**
     * Initialize resource model
     *
     * @return void
     */
	 public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\UrlInterface $url,
		\Magerubik\All\Helper\Image $imageHelp,
        \Magerubik\Blog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
		\Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
		$this->_url = $url;
		$this->imageHelper = $imageHelp;
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_productCollectionFactory = $productCollectionFactory;
		$this->filterProvider = $filterProvider;
    }
    protected function _construct()
    {
		$this->_init(PostResource::class);
		parent::_construct();
        $this->_cacheTag = self::CACHE_TAG;
    }
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
	/**
     * @param $key
     * @return string
     * @throws \Exception
     */
    private function getContent($key)
    {
        $content = $this->getData($key) ? : '';
        $content = $this->filterProvider->getPageFilter()->filter($content);
        return $content;
    }
	/**
     * Retrieve model title
     * @param  boolean $plural
     * @return string
     */
    public function getOwnTitle($plural = false)
    {
        return $plural ? 'Post' : 'Posts';
    }
    /**
     * Retrieve true if post is active
     * @return boolean [description]
     */
    public function isActive()
    {
        return ($this->getStatus() == self::STATUS_ENABLED);
    }
    /**
     * Retrieve available post statuses
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_DISABLED => __('Disabled'), self::STATUS_ENABLED => __('Enabled')];
    }
    /**
     * Check if post identifier exist for specific store
     * return post id if post exists
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
     * Retrieve post url route path
     * @return string
     */
    public function getUrl()
    {
        return 'blog/post/'.$this->getIdentifier();
    }
    /**
     * Retrieve post url
     * @return string
     */
    public function getPostUrl()
    {
        return $this->_url->getUrl($this->getUrl());
    }
	/**
     * Retrieve post url
     * @return string
     */
    public function getThumbnailimageSrc()
    {
		$src = $this->getThumbnailimage();
        return $src ? $this->imageHelper->getImageUrl($src, 'blog/') : '';
    }
	/**
     * Retrieve post url
     * @return string
     */
    public function getDetailimageSrc()
    {
		$src = $this->getDetailimage();
        return $src ? $this->imageHelper->getImageUrl($src, 'blog/') : '';
    }
    /**
     * Retrieve post parent categories
     * @return \Magerubik\Blog\Model\ResourceModel\Category\Collection
     */
    public function getParentCategories()
    {
        if (is_null($this->_parentCategories)) {
            $this->_parentCategories = $this->_categoryCollectionFactory->create()
                ->addFieldToFilter('category_id', array('in' => $this->getCategories()))
                ->addStoreFilter($this->getStoreId())
                ->addActiveFilter();
        }
        return $this->_parentCategories;
    }
    /**
     * Retrieve post parent categories count
     * @return int
     */
    public function getCategoriesCount()
    {
        return count($this->getParentCategories());
    }
    /**
     * Retrieve post related posts
     * @return \Magerubik\Blog\Model\ResourceModel\Post\Collection
     */
    public function getRelatedPosts()
    {
        return $this->getCollection()
            ->addFieldToFilter('post_id', array('in' => $this->getRelatedPostIds() ?: array(0)))
            ->addStoreFilter($this->getStoreId());
    }
    /**
     * Retrieve post related products
     * @return \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    public function getRelatedProducts()
    {
        $collection = $this->_productCollectionFactory->create()->addFieldToFilter('entity_id', array('in' => $this->getRelatedProductIds() ?: array(0)));
        if ($storeIds = $this->getStoreId()) {
            $collection->addStoreFilter($storeIds[0]);
        }
        return $collection;
    }
	/**
     * @return array
     */
    public function getStores()
    {
        return $this->_getData(PostInterface::STORES);
    }
	/**
     * @return array
     */
    public function getCategories()
    {
        return $this->_getData(PostInterface::CATEGORIES);
    }
	/**
     * @return int
     */
    public function getPostId()
    {
        return (int)$this->_getData(PostInterface::POST_ID);
    }
    public function setPostId($postId)
    {
        $this->setData(PostInterface::POST_ID, $postId);
        return $this;
    }
	/**
     * @return int
     */
    public function getIsActive()
    {
        return $this->_getData(PostInterface::STATUS);
    }
    public function setIsActive($isActive)
    {
        $this->setData(PostInterface::STATUS, $isActive);
        return $this;
    }
	/**
     * @return string
     */
    public function getTitle()
    {
        return $this->_getData(PostInterface::TITLE);
    }
    public function setTitle($title)
    {
        $this->setData(PostInterface::TITLE, $title);
        return $this;
    }
	/**
     * @return int
     */
    public function getIdentifier()
    {
        return $this->_getData(PostInterface::URL_KEY);
    }
    public function setIdentifier($identifier)
    {
        $this->setData(PostInterface::URL_KEY, $identifier);
        return $this;
    }
	/**
     * @return string|null
     */
	public function getShortContent()
    {
        $content = $this->getContent(PostInterface::SHORT_CONTENT);
        return str_replace('target="_self"', '', $content);
    }
	public function setShortContent($shortContent)
    {
        $this->setData(PostInterface::SHORT_CONTENT, $shortContent);
        return $this;
    }
	/**
     * @return string
     */
	public function getFullContent()
    {
        $content = $this->getContent(PostInterface::FULL_CONTENT);
        return str_replace('target="_self"', '', $content);
    }
	public function setFullContent($content)
    {
        $this->setData(PostInterface::FULL_CONTENT, $content);
        return $this;
    }
	/**
     * @return string
     */
    public function getRelatedPostIds()
    {
        return $this->_getData(PostInterface::RELATED_POST_IDS);
    }
    public function setRelatedPostIds($ids)
    {
        $this->setData(PostInterface::RELATED_POST_IDS, $ids);
        return $this;
    }
		/**
     * @return string
     */
    public function getRelatedProductIds()
    {
        return $this->_getData(PostInterface::RELATED_PRODUCT_IDS);
    }
    public function setRelatedProductIds($ids)
    {
        $this->setData(PostInterface::RELATED_PRODUCT_IDS, $ids);
        return $this;
    }
	/**
     * @return string|null
     */
    public function getMetaKeywords()
    {
        return $this->_getData(PostInterface::META_KEYWORDS);
    }
    public function setMetaKeywords($metaKeywords)
    {
        $this->setData(PostInterface::META_KEYWORDS, $metaKeywords);
        return $this;
    }
	/**
     * @return string|null
     */
    public function getMetaDescription()
    {
        return $this->_getData(PostInterface::META_DESCRIPTION);
    }
    public function setMetaDescription($metaDescription)
    {
        $this->setData(PostInterface::META_DESCRIPTION, $metaDescription);
        return $this;
    }
	/**
     * @return string
     */
    public function getCreationTime()
    {
        return $this->_getData(PostInterface::CREATEION_TIME);
    }
    public function setCreationTime($creationTime)
    {
        $this->setData(PostInterface::CREATEION_TIME, $creationTime);
        return $this;
    }
	/**
     * @return string
     */
    public function getUpdatedTime()
    {
        return $this->_getData(PostInterface::UPDATED_TIME);
    }
    public function setUpdatedTime($updateTime)
    {
        $this->setData(PostInterface::UPDATED_TIME, $updateTime);
        return $this;
    }
	/**
     * @return string
     */
    public function getPublishTime()
    {
        return $this->_getData(PostInterface::PUBLISH_TIME);
    }
    public function setPublishTime($publishTime)
    {
        $this->setData(PostInterface::PUBLISH_TIME, $publishTime);
        return $this;
    }
	/**
     * @return string|null
     */
    public function getDetailimage()
    {
        return $this->_getData(PostInterface::DETAILIMAGE);
    }
    public function setDetailimage($detailimage)
    {
        $this->setData(PostInterface::DETAILIMAGE, $detailimage);
        return $this;
    }
	/**
     * @return string|null
     */
    public function getThumbnailimage()
    {
        return $this->_getData(PostInterface::THUMBNAILIMAGE);
    }
    public function setThumbnailimage($thumbnailimage)
    {
        $this->setData(PostInterface::THUMBNAILIMAGE, $thumbnailimage);
        return $this;
    }
	/**
     * @param $name
     * @param $thumbnail
     * @return $this
     */
    public function setThumbnail($name, $thumbnail)
    {
        return $this->setData($name, $thumbnail);
    }
}