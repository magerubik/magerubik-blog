<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Controller\Adminhtml\Post;
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
    const ADMIN_RESOURCE = 'Magerubik_Blog::post';
    public function __construct(
        Action\Context $context,
        Filter $filter,
        LoggerInterface $logger,
        \Magerubik\Blog\Model\ResourceModel\Post\CollectionFactory $collectionFactory
    ) {
        parent::__construct($context, $filter, $logger);
        $this->_collectionFactory = $collectionFactory;
    }
    public function getCollection()
    {
        return $this->_collectionFactory->create();
    }
}