<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Api;
use Magerubik\Blog\Model\ResourceModel\Category\Collection;
use Magento\Framework\Exception\NoSuchEntityException;
/**
 * @api
 */
interface CategoryRepositoryInterface
{
    /**
     * Save
     *
     * @param \Magerubik\Blog\Api\Data\CategoryInterface $category
     *
     * @return \Magerubik\Blog\Api\Data\CategoryInterface
     */
    public function save(\Magerubik\Blog\Api\Data\CategoryInterface $category);
    /**
     * Get by id
     *
     * @param int $categoryId
     *
     * @return \Magerubik\Blog\Api\Data\CategoryInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($categoryId);
    /**
     * @param $urlKey
     * @return \Magerubik\Blog\Model\Categories
     */
    public function getByUrlKey($urlKey);
    /**
     * @return \Magerubik\Blog\Model\Categories
     */
    public function getCategory();
    /**
     * Delete
     *
     * @param \Magerubik\Blog\Api\Data\CategoryInterface $category
     *
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Magerubik\Blog\Api\Data\CategoryInterface $category);
    /**
     * Delete by id
     *
     * @param int $categoryId
     *
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($categoryId);
    /**
     * Lists
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \Magento\Framework\Api\SearchResultsInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
    /**
     * @return \Magento\Framework\DataObject[]
     */
    public function getAllCategories();
    /**
     * @param $categoryId
     *
     * @return array
     */
    public function getStores($categoryId);
    /**
     * @param $postId
     * @return Collection
     */
    public function getCategoriesByPost($postId);
    /**
     * @param null $storeId
     * @return Collection
     */
    public function getActiveCategories($storeId = null);
    /**
     * @param array $categoryIds
     * @return Collection
     */
    public function getCategoriesByIds($categoryIds = []);
    /**
     * @param $parentId
     * @param $limit
     * @param $storeId
     * @return Collection
     * @throws NoSuchEntityException
     */
    public function getChildrenCategories($parentId, $limit = 0, $storeId = null);
    /**
     * @param $categoryId
     * @param int $storeId
     * @param bool $isAddDefaultStore
     * @return \Magento\Framework\DataObject
     */
    public function getByIdAndStore($categoryId, $storeId = 0, $isAddDefaultStore = true);
}