<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Api\Data;
interface PostInterface
{
    const POST_ID = 'post_id';
    const STORES = 'store_id';
    const CATEGORIES = 'categories';
    const STATUS = 'is_active';
    const TITLE = 'title';
    const URL_KEY = 'identifier';
    const SHORT_CONTENT = 'short_content';
    const FULL_CONTENT = 'content';
    const META_KEYWORDS = 'meta_keywords';
    const META_DESCRIPTION = 'meta_description';
    const CREATEION_TIME = 'creation_time';
    const UPDATED_TIME = 'updated_time';
    const PUBLISH_TIME = 'publish_time';
    const DISPLAY_SHORT_CONTENT = 'display_short_content';
    const THUMBNAILIMAGE = 'thumbnailimage';
    const DETAILIMAGE = 'detailimage';
    const RELATED_POST_IDS = 'related_post_ids';
	const RELATED_PRODUCT_IDS = 'related_product_ids';
    /**
     * @return int
     */
    public function getPostId();
    /**
     * @return array
     */
    public function getStores();
    /**
     * @return array
     */
    public function getCategories();
    /**
     * @param int $postId
     */
    public function setPostId($postId);
    /**
     * @return int
     */
    public function getIsActive();
    /**
     * @param int $isActive
     */
    public function setIsActive($isActive);
    /**
     * @return string
     */
    public function getTitle();
    /**
     * @param string $title
     */
    public function setTitle($title);
    /**
     * @return string
     */
    public function getIdentifier();
    /**
     * @param string $identifier
     */
    public function setIdentifier($identifier);
    /**
     * @return string|null
     */
    public function getShortContent();
    /**
     * @param string|null $shortContent
     */
    public function setShortContent($shortContent);
    /**
     * @return string
     */
    public function getFullContent();
    /**
     * @param string $content
     */
    public function setFullContent($content);

    /**
     * @return string
     */
    public function getRelatedPostIds();
    /**
     * @param string $ids
     */
    public function setRelatedPostIds($ids);
	    /**
     * @return string
     */
    public function getRelatedProductIds();
    /**
     * @param string $ids
     */
    public function setRelatedProductIds($ids);
    /**
     * @return string|null
     */
    public function getMetaKeywords();
    /**
     * @param string|null $metaKeywords
     */
    public function setMetaKeywords($metaKeywords);
    /**
     * @return string|null
     */
    public function getMetaDescription();
    /**
     * @param string|null $metaDescription
     */
    public function setMetaDescription($metaDescription);
    /**
     * @return string
     */
    public function getCreationTime();
    /**
     * @param string $creationTime
     */
    public function setCreationTime($creationTime);
    /**
     * @return string
     */
    public function getUpdatedTime();
    /**
     * @param string $updatedTime
     */
    public function setUpdatedTime($updatedTime);
    /**
     * @return string
     */
    public function getPublishTime();
    /**
     * @param string $publishTime
     */
    public function setPublishTime($publishTime);
	 /**
     * @return string|null
     */
    public function getDetailimage();
    /**
     * @param string|null $detailimage
     */
    public function setDetailimage($detailimage);
    /**
     * @return string|null
     */
    public function getThumbnailimage();
    /**
     * @param string|null $thumbnailimage
     */
    public function setThumbnailimage($thumbnailimage);
    /**
     * @param $name
     * @param $thumbnail
     */
    public function setThumbnail($name, $thumbnail);
}