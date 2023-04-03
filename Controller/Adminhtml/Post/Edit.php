<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Controller\Adminhtml\Post;
/**
 * Blog post edit controller
 */
class Edit extends \Magerubik\Blog\Controller\Adminhtml\Post
{
	const CURRENT_BLOG_POST = 'current_mrblog_post';
    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->getPostRepository()->getPost();
        if ($id) {
            try {
                $model = $this->getPostRepository()->getById($id);
            } catch (\Exception $e) {
                $this->getMessageManager()->addErrorMessage($e->getMessage());
                $this->_redirect('*/*');
                return;
            }
        }
        $data = $this->_getSession()->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }
        $this->getRegistry()->register(self::CURRENT_BLOG_POST, $model);
        $this->initAction();
        $title = $model->getId() ? __('Edit Post `%1`', $model->getTitle()) : __("Add New Post");
        $this->_view->getPage()->getConfig()->getTitle()->prepend($title);
        $this->_view->renderLayout();
    }
    /**
     * @return $this
     */
    private function initAction()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu('Magerubik_Blog::post')->_addBreadcrumb(
            __('Magerubik Blog Post'),
            __('Magerubik Blog Post')
        );
        return $this;
    }
}