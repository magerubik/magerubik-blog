<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Controller\Adminhtml\Category;
/**
 * Blog category list controller
 */
class Index extends \Magerubik\Blog\Controller\Adminhtml\Category
{
	/**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->getPageFactory()->create();
        $resultPage->setActiveMenu('Magerubik_Blog::category');
        $resultPage->getConfig()->getTitle()->prepend(__('Category'));
        $resultPage->addBreadcrumb(__('Category'), __('Category'));
        return $resultPage;
    }
}