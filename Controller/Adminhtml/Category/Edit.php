<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Controller\Adminhtml\Category;
/**
 * Blog category edit controller
 */
use Magento\Framework\App\ResponseInterface;
class Edit extends \Magerubik\Blog\Controller\Adminhtml\Category
{
    const CURRENT_BLOG_CATEGORY = 'current_mrblog_category';
    /**
     * Dispatch request
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface|void
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $storeId = $this->getRequest()->getParam('store');
        $model = $this->getCategoryRepository()->getCategory();
        if ($id) {
            try {
                $model = $this->getCategoryRepository()->getById($id);
            } catch (\Exception $e) {
                $this->getMessageManager()->addErrorMessage($e->getMessage());
                return $this->_redirect('*/*');
            }
        }
        $data = $this->_getSession()->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }
        $this->getRegistry()->register(self::CURRENT_BLOG_CATEGORY, $model);
        $this->initAction();
        if ($model->getId()) {
            $title = __('Edit Category `%1`', $model->getTitle());
        } else {
            $title = __('Add New Category');
        }
        $this->_view->getPage()->getConfig()->getTitle()->prepend($title);
        $this->_view->renderLayout();
    }
    /**
     * @return $this
     */
    private function initAction()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu('Magerubik_Blog::category')->_addBreadcrumb(
            __('Magerubik Blog Category'),
            __('Magerubik Blog Category')
        );
        return $this;
    }
}