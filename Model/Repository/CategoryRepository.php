<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Model\Repository;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Ui\Api\Data\BookmarkSearchResultsInterfaceFactory;
use Magerubik\Blog\Api\CategoryRepositoryInterface;
use Magerubik\Blog\Api\Data\CategoryInterface;
use Magerubik\Blog\Model\CategoryFactory;
use Magerubik\Blog\Model\ResourceModel\Category as CategoryResource;
use Magerubik\Blog\Model\ResourceModel\Category\Collection;
use Magerubik\Blog\Model\ResourceModel\Category\CollectionFactory;
use Magerubik\All\Model\Source\Status as CategoryStatus;
class CategoryRepository implements CategoryRepositoryInterface
{
    const LEVEL_LIMIT = 3;
	/**
     * @var BookmarkSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;
    /**
     * @var CategoryFactory
     */
    private $categoryFactory;
    /**
     * @var CategoryResource
     */
    private $categoryResource;
    /**
     * Model data storage
     *
     * @var array
     */
    private $categories;
    /**
     * @var CollectionFactory
     */
    private $categoryCollectionFactory;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var \Magerubik\Blog\Helper\Configuration
     */
    private $confiHelper;
    public function __construct(
        BookmarkSearchResultsInterfaceFactory $searchResultsFactory,
        CategoryFactory $categoryFactory,
        CategoryResource $categoryResource,
        CollectionFactory $categoryCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magerubik\Blog\Helper\Configuration $confiHelper
    ) {
        $this->searchResultsFactory = $searchResultsFactory;
        $this->categoryFactory = $categoryFactory;
        $this->categoryResource = $categoryResource;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->storeManager = $storeManager;
        $this->confiHelper = $confiHelper;
    }
    /**
     * @param CategoryInterface $categories
     *
     * @return CategoryInterface
     * @throws CouldNotSaveException
     */
    public function save(CategoryInterface $categories)
    {
        try {
            if ($categories->getCategoryId()) {
                $categories = $this->getById($categories->getCategoryId())->addData($categories->getData());
            } else {
                $categories->setCategoryId(null);
            }
            $this->categoryResource->save($categories);
            unset($this->category[$categories->getCategoryId()]);
        } catch (\Exception $e) {
            if ($categories->getCategoryId()) {
                throw new CouldNotSaveException(
                    __(
                        'Unable to save categories with ID %1. Error: %2',
                        [$categories->getCategoryId(), $e->getMessage()]
                    )
                );
            }
            throw new CouldNotSaveException(__('Unable to save new categories. Error: %1', $e->getMessage()));
        }
        return $categories;
    }
    /**
     * @param int $categoryId
     *
     * @return \Magerubik\Blog\Model\Category
     * @throws NoSuchEntityException
     */
    public function getById($categoryId)
    {
        if (!isset($this->categories[$categoryId])) {
            /** @var \Magerubik\Blog\Model\Category $categories */
            $categories = $this->categoryFactory->create();
            $this->categoryResource->load($categories, $categoryId);
            if (!$categories->getCategoryId()) {
                throw new NoSuchEntityException(__('Category with specified ID "%1" not found.', $categoryId));
            }
            $this->categories[$categoryId] = $categories;
        }
        return $this->categories[$categoryId];
    }
    /**
     * @param string $urlKey
     * @return \Magerubik\Blog\Model\Category|\Magento\Framework\DataObject
     * @throws NoSuchEntityException
     */
    public function getByUrlKey($urlKey)
    {
        $collection = $this->getActiveCategories();
        $collection->addFieldToFilter('url_key', $urlKey);
        $collection->setLimit(1);
        return $collection->getFirstItem();
    }
    /**
     * @param CategoryInterface $categories
     *
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(CategoryInterface $categories)
    {
        try {
            $this->categoryResource->delete($categories);
            unset($this->categories[$categories->getCategoryId()]);
        } catch (\Exception $e) {
            if ($categories->getCategoryId()) {
                throw new CouldNotDeleteException(
                    __(
                        'Unable to remove categories with ID %1. Error: %2',
                        [$categories->getCategoryId(), $e->getMessage()]
                    )
                );
            }
            throw new CouldNotDeleteException(__('Unable to remove categories. Error: %1', $e->getMessage()));
        }
        return true;
    }
    /**
     * @param int $categoryId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($categoryId)
    {
        $categoriesModel = $this->getById($categoryId);
        $this->delete($categoriesModel);
        return true;
    }
    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Magento\Framework\Api\SearchResultsInterface|\Magento\Ui\Api\Data\BookmarkSearchResultsInterface
     * @throws NoSuchEntityException
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        /** @var \Magerubik\Blog\Model\ResourceModel\Category\Collection $categoriesCollection */
        $categoriesCollection = $this->categoryCollectionFactory->create();
        // Add filters from root filter group to the collection
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $categoriesCollection);
        }
        $searchResults->setTotalCount($categoriesCollection->getSize());
        $sortOrders = $searchCriteria->getSortOrders();
        if ($sortOrders) {
            $this->addOrderToCollection($sortOrders, $categoriesCollection);
        }
        $categoriesCollection->setCurPage($searchCriteria->getCurrentPage());
        $categoriesCollection->setPageSize($searchCriteria->getPageSize());
        $categories = [];
        /** @var CategoryInterface $categories */
        foreach ($categoriesCollection->getItems() as $categoryItem) {
            $categories[] = $this->getById($categoryItem->getCategoryId());
        }
        $searchResults->setItems($categories);
        return $searchResults;
    }
    /**
     * Helper function that adds a FilterGroup to the collection.
     *
     * @param FilterGroup $filterGroup
     * @param Collection $categoriesCollection
     *
     * @return void
     */
    private function addFilterGroupToCollection(FilterGroup $filterGroup, Collection $categoriesCollection)
    {
        foreach ($filterGroup->getFilters() as $filter) {
            $condition = $filter->getConditionType() ?: 'eq';
            $categoriesCollection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
        }
    }
    /**
     * Helper function that adds a SortOrder to the collection.
     *
     * @param SortOrder[] $sortOrders
     * @param Collection $categoriesCollection
     *
     * @return void
     */
    private function addOrderToCollection($sortOrders, Collection $categoriesCollection)
    {
        /** @var SortOrder $sortOrder */
        foreach ($sortOrders as $sortOrder) {
            $field = $sortOrder->getField();
            $categoriesCollection->addOrder(
                $field,
                ($sortOrder->getDirection() == SortOrder::SORT_DESC) ? SortOrder::SORT_DESC : SortOrder::SORT_ASC
            );
        }
    }
    /**
     * @return \Magento\Framework\DataObject[]
     */
    public function getAllCategories()
    {
        return $this->categoryCollectionFactory->create()->addDefaultStore()->getItems();
    }
    /**
     * @return \Magerubik\Blog\Model\Category
     */
    public function getCategory()
    {
        return $this->categoryFactory->create();
    }
    /**
     * @param $categoryId
     *
     * @return array
     */
    public function getStores($categoryId)
    {
        return $this->categoryResource->getStores($categoryId);
    }
    /**
     * @param $postId
     * @return Collection
     * @throws NoSuchEntityException
     */
    public function getCategoriesByPost($postId)
    {
        return $this->getActiveCategories()->addPostFilter($postId);
    }
    /**
     * @param array $categoryIds
     * @return Collection
     * @throws NoSuchEntityException
     */
    public function getCategoriesByIds($categoryIds = [])
    {
        return $this->getActiveCategories()->addIdFilter($categoryIds);
    }
    /**
     * @param null $storeId
     * @return Collection
     * @throws NoSuchEntityException
     */
    public function getActiveCategories($storeId = null)
    {
        $categories = $this->categoryCollectionFactory->create();
        $categories->addStatusFilter(CategoryStatus::STATUS_ENABLED);
        $storeId = $storeId === null ? $this->storeManager->getStore()->getId() : $storeId;
        return $categories;
    }
    /**
     * @param $parentId
     * @param $limit
     * @param int $storeId
     * @return Collection
     * @throws NoSuchEntityException
     */
    public function getChildrenCategories($parentId, $limit = 0, $storeId = null)
    {
        $collection = $this->getActiveCategories($storeId);
        $collection->addFieldToFilter(CategoryInterface::PARENT_ID, $parentId);
        $collection->setPageSize($limit ?: $this->confiHelper->getCategoriesLimit());
        $collection->getSelect()->where('main_table.level <= ?', self::LEVEL_LIMIT);
        $collection->setSortOrder('asc');
        return $collection;
    }
    /**
     * @param int $categoryId
     * @param int $storeId
     * @param bool $isAddDefaultStore
     * @return \Magento\Framework\DataObject
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByIdAndStore($categoryId, $storeId = 0, $isAddDefaultStore = true)
    {
        $collection = $this->categoryCollectionFactory->create();
        $collection->addStore($storeId);
        $collection->addFieldToFilter(CategoryInterface::CATEGORY_ID, $categoryId);
        $collection->setLimit(1);
        return $collection->getFirstItem();
    }
}