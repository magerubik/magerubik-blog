<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Ui\Component\Form;
use Magento\Framework\Data\OptionSourceInterface;
use Magerubik\Blog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magerubik\Blog\Controller\Adminhtml\Category\Edit as EditController;
class Category implements OptionSourceInterface
{
    /**
     * @var CategoryCollectionFactory
     */
    private $categoryCollectionFactory;
    /**
	  * @var \Magento\Framework\Registry
	 */
	protected $_registry;
    /**
     * @var array
     */
    private $categoryTree;
    public function __construct(
        CategoryCollectionFactory $categoryCollectionFactory,
        \Magento\Framework\Registry $registry
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->_registry = $registry;
    }
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return $this->getCategoryTree();
    }
    /**
     * Retrieve category tree
     *
     * @param bool $displayRoot
     * @return array
     */
    public function getCategoryTree($displayRoot = false)
    {
        if ($this->categoryTree === null) {
            $collection = $this->categoryCollectionFactory->create();
            if ($category = $this->_registry->registry('mrblog_current_category')) {
                $excludePath = $category->getPath() . '/' . $category->getCategoryId();
                $collection
                    ->getSelect()
                    ->where('main_table.path NOT LIKE "?%"', $excludePath);
                $collection->addFieldToFilter('category_id', ['neq' => $category->getCategoryId()]);
            }
            $categoryById = [
                0 => [
                    'value' => 0,
                    'label' => __('Root Category'),
                    'is_active' => true
                ],
            ];
            foreach ($collection as $category) {
                foreach ([$category->getCategoryId(), (int)$category->getParentId()] as $categoryId) {
                    if (!isset($categoryById[$categoryId])) {
                        $categoryById[$categoryId] = ['value' => (string)$categoryId];
                    }
                }
                $categoryById[$category->getId()]['is_active'] = $category->getStatus();
                $categoryById[$category->getId()]['label'] = $category->getTitle();
                $categoryById[(int)$category->getParentId()]['optgroup'][] = &$categoryById[$category->getId()];
            }
            if ($displayRoot) {
                $this->categoryTree = [$categoryById[0]];
            } else {
                $this->categoryTree = isset($categoryById[0]['optgroup'])
                    ? $categoryById[0]['optgroup']
                    : [];
            }
        }
        return $this->categoryTree;
    }
    /**
     * @return CategoryCollectionFactory
     */
    public function getCategoryCollectionFactory()
    {
        return $this->categoryCollectionFactory;
    }
}