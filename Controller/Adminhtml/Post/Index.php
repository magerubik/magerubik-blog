<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Controller\Adminhtml\Post;
/**
 * Blog post list controller
 */
class Index extends \Magerubik\Blog\Controller\Adminhtml\Post
{
	/**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->getPageFactory()->create();
        $resultPage->setActiveMenu('Magerubik_Blog::post');
        $resultPage->getConfig()->getTitle()->prepend(__('Post'));
        $resultPage->addBreadcrumb(__('Post'), __('Post'));
        return $resultPage;
    }
}