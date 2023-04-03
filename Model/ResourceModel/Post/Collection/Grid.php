<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Model\ResourceModel\Post\Collection;
use Magerubik\Blog\Model\ResourceModel\Post\Collection;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Api\Search\AggregationInterface;
/**
 * Class
 */
class Grid extends \Magerubik\Blog\Model\ResourceModel\Post\Collection implements SearchResultInterface
{
    protected $_map = [
        'fields' => [
            'identifier' => 'main_table.identifier',
            'title' => 'main_table.title',
            'post_id' => 'main_table.post_id'
        ]
    ];
    /**
     * @var AggregationInterface
     */
    private $aggregations;
    /**
     * Initialize db select
     *
     * @return $this
     */
    protected function _initSelect()
    {
        $this->addStores();
        $this->addCategories();
        $this->getSelect()
            ->group('main_table.post_id')
            ->columns('GROUP_CONCAT(store_id SEPARATOR ",") as store_id')
            ->columns('GROUP_CONCAT(categories.category_id SEPARATOR ",") as category_id');
        parent::_initSelect();
        return $this;
    }
	/**
     * @return $this
     */
    public function addStores()
    {
        $this->getSelect()
            ->joinLeft(
                ['store' => $this->getTable('magerubik_blog_post_store')],
                'main_table.post_id = store.post_id',
                []
            );
        return $this;
    }
	/**
     * @return $this
     */
    protected function addCategories()
    {
        $this->getSelect()
            ->joinLeft(
                ['categories' => $this->getTable('magerubik_blog_post_category')],
                'main_table.post_id = categories.post_id',
                []
            );
        return $this;
    }
    /**
     * @param $categoryIds
     *
     * @return $this
     */
    public function addCategoryFilter($categoryIds)
    {
        $categoryIds = is_array($categoryIds) ? $categoryIds : [$categoryIds];
        $this->getSelect()->where('categories.category_id IN (?)', $categoryIds);
        return $this;
    }
    /**
     * Set items list.
     *
     * @param \Magento\Framework\Api\Search\DocumentInterface[] $items
     *
     * @return $this
     */
    public function setItems(array $items = null)
    {
        return $this;
    }
    /**
     * @return \Magento\Framework\Api\Search\AggregationInterface
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }
    /**
     * @param \Magento\Framework\Api\Search\AggregationInterface $aggregations
     *
     * @return $this
     */
    public function setAggregations($aggregations)
    {
        $this->aggregations = $aggregations;
        return $this;
    }
    /**
     * Get search criteria.
     *
     * @return \Magento\Framework\Api\Search\SearchCriteriaInterface
     */
    public function getSearchCriteria()
    {
        return null;
    }
    /**
     * Set search criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return $this
     */
    public function setSearchCriteria(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        return $this;
    }
    /**
     * Get total count.
     *
     * @return int
     */
    public function getTotalCount()
    {
        return $this->getSize();
    }
    /**
     * Set total count.
     *
     * @param int $totalCount
     *
     * @return $this
     */
    public function setTotalCount($totalCount)
    {
        return $this;
    }
}