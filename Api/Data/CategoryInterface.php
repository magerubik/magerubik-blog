<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Api\Data;
interface CategoryInterface
{
    const ROUTE_CATEGORY = 'category';
    const CATEGORY_ID = 'category_id';
    const TITLE = 'title';
    const URL_KEY = 'identifier';
    const STATUS = 'is_active';
    const POSITION = 'position';
    const META_TITLE = 'meta_title';
    const META_KEYWORDS = 'meta_keywords ';
    const META_DESCRIPTION = 'meta_description';
    const PARENT_ID = 'parent_id';
    const PATH = 'path';
    const LEVEL = 'level';
    const ROOT_CATEGORY_ID = "0";
    const STORE_ID = "store_id";
    /**
     * @return int
     */
    public function getCategoryId();
    /**
     * @param int $categoryId
     */
    public function setCategoryId($categoryId);
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
     * @return int
     */
    public function getIsActive();
    /**
     * @param int $isActive
     */
    public function setIsActive($isActive);
    /**
     * @return int
     */
    public function getPosition();
    /**
     * @param int $position
     */
    public function setPosition($position);
    /**
     * @return string|null
     */
    public function getMetaTitle();
    /**
     * @param string|null $metaTitle
     */
    public function setMetaTitle($metaTitle);
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
     *
     * @return \Magerubik\Blog\Api\Data\CategoryInterface
     */
    public function setMetaDescription($metaDescription);
    /**
     * @return int
     */
    public function getStoreId();
    /**
     * @param int $storeId
     */
    public function setStoreId($storeId);
    /**
     * @return int
     */
    public function getParentId();
	/**
     * @param int $parentId
     */
    public function setParentId($parentId);
    /**
     * @return string
     */
    public function getPath();
    /**
     * @param string $path
     */
    public function setPath($path);
    /**
     * @return int
     */
    public function getLevel();
}