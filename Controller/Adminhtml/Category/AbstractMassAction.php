<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Controller\Adminhtml\Category;
use Magento\Backend\App\Action;
use Magento\Ui\Component\MassAction\Filter;
use Psr\Log\LoggerInterface;
/**
 * Class AbstractMassAction
 */
abstract class AbstractMassAction extends \Magerubik\All\Controller\Adminhtml\AbstractMassAction
{
    /**
     * Authorization level of a basic admin session
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Magerubik_Blog::category';
    /**
     * @var \Magerubik\Blog\Model\Repository\CategoryRepository
     */
    private $repository;
    /**
     * @var \Magerubik\Blog\Model\ResourceModel\Category\CollectionFactory
     */
    private $collectionFactory;
    public function __construct(
        Action\Context $context,
        Filter $filter,
        LoggerInterface $logger,
        \Magerubik\Blog\Model\Repository\CategoryRepository $repository,
        \Magerubik\Blog\Model\ResourceModel\Category\CollectionFactory $collectionFactory
    ) {
        parent::__construct($context, $filter, $logger);
        $this->repository = $repository;
        $this->collectionFactory = $collectionFactory;
    }
    public function getCollection()
    {
        return $this->collectionFactory->create();
    }
    public function getRepository()
    {
        return $this->repository;
    }
}