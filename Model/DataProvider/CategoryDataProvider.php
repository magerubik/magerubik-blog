<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Model\DataProvider;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magerubik\Blog\Model\Category;
use Magerubik\Blog\Api\Data\CategoryInterface;
use Magerubik\Blog\Model\ResourceModel\Category\CollectionFactory;
use Magerubik\Blog\Controller\Adminhtml\Category\Edit;
use Magerubik\Blog\Model\Repository\CategoryRepository;
use Magerubik\Blog\Model\BlogRegistry;
use Magerubik\All\Model\DataProvider\Traits\DataProviderTrait;
class CategoryDataProvider extends AbstractDataProvider
{
    use DataProviderTrait;
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;
    /**
     * @var PoolInterface
     */
    private $pool;
    /**
     * @var CategoryRepository
     */
    private $repository;
    /**
     * @var Registry
     */
    private $_registry;
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        DataPersistorInterface $dataPersistor,
        CollectionFactory $collectionFactory,
        CategoryRepository $repository,
        PoolInterface $pool,
        \Magento\Framework\Registry $_registry,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->repository = $repository;
        $this->dataPersistor = $dataPersistor;
        $this->pool = $pool;
        $this->_registry = $_registry;
    }
    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getData()
    {
        $data = parent::getData();
		if ($data['totalRecords'] > 0) {
            $categoryId = (int)$data['items'][0]['category_id'];
            $model = $this->getCategory($categoryId);
            if ($model) {
                $postData = $model->getData();
                $data[$categoryId] = $postData;
            }
        }
        if ($savedData = $this->dataPersistor->get(Category::PERSISTENT_NAME)) {
            $savedCategoryId = isset($savedData['category_id']) ? $savedData['category_id'] : null;
            $data[$savedCategoryId] = isset($data[$savedCategoryId])
                ? array_merge($data[$savedCategoryId], $savedData)
                : $savedData;
            $this->dataPersistor->clear(Category::PERSISTENT_NAME);
        }
        return $data;
    }
	/**
     * @param $categoryId
     * @return CategoryInterface|null
     */
    private function getCategory($categoryId)
    {
        try {
            $model = $this->repository->getById($categoryId);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $model = null;
        }
        return $model;
    }
    /**
     * @return array
     */
    public function getFieldsByStore()
    {
        return CategoryInterface::FIELDS_BY_STORE;
    }
}