<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Controller\Adminhtml;
use Magento\Framework\App\Request\DataPersistorInterface;
abstract class Post extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;
    /**
     * @var \Magerubik\Blog\Api\PostRepositoryInterface
     */
    private $postRepository;
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;
	/**
     * @var \Magerubik\All\Model\ImageProcessor
     */
    private $imageProcessor;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    private $timezone;
	/**
     * @var \Magento\Framework\Registry
     */
    private $_registry;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        DataPersistorInterface $dataPersistor,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
		\Magento\Framework\Registry $_registry,
		\Magerubik\Blog\Api\PostRepositoryInterface $postRepository,
		\Magerubik\All\Model\ImageProcessor $imageProcessor,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->postRepository = $postRepository;
        $this->dataPersistor = $dataPersistor;
        $this->timezone = $timezone;
		$this->imageProcessor = $imageProcessor;
        $this->logger = $logger;
        $this->_registry = $_registry;
    }
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magerubik_Blog::post');
    }
    public function getImageProcessor()
    {
        return $this->imageProcessor;
    }
    public function getLogger()
    {
        return $this->logger;
    }
    public function getDataPersistor()
    {
        return $this->dataPersistor;
    }
    public function getPostRepository()
    {
        return $this->postRepository;
    }
    public function getPageFactory()
    {
        return $this->resultPageFactory;
    }
    public function getTimezone()
    {
        return $this->timezone;
    }
	public function getRegistry()
    {
		return $this->_registry;
    }
}