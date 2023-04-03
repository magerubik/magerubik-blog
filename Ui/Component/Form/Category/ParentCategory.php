<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Ui\Component\Form\Category;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Catalog\Model\Category as CategoryModel;
use Magerubik\Blog\Api\Data\CategoryInterface;
use Magerubik\Blog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
/**
 * Class
 */
class ParentCategory extends \Magerubik\Blog\Ui\Component\Form\Category implements OptionSourceInterface
{
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return $this->getCategoryTree(true);
    }
}