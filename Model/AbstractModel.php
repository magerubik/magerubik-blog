<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Model;
use Magerubik\Blog\Helper\Settings;
class AbstractModel extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManagerInterface;
    /**
     * @var null
     */
    private $storeId = null;
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magerubik\Blog\Helper\Configuration $settings,
        \Magento\Store\Model\StoreManagerInterface $storeManagerInterface,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->storeManagerInterface = $storeManagerInterface;
        $this->settings = $settings;
    }
    /**
     * @param $storeId
     *
     * @return $this
     */
    public function setStoreId($storeId)
    {
        $this->storeId = $storeId;
        $this->setData('store_id', $storeId);
        return $this;
    }
    /**
     * @return string
     */
    public function getRoute()
    {
        return  '';
    }
    /**
     * @return int|null
     */
    public function getStoreId()
    {
        return $this->hasData('store_id') ? $this->getData('store_id') : $this->storeId;
    }
    /**
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCurrentStoreId()
    {
        return $this->storeManagerInterface->getStore()->getId();
    }
}