<?php
/**
 * @author Magerubik Team
 * @copyright Copyright (c) 2020 Magerubik (https://www.magerubik.com)
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Controller\Category;
/**
 * Blog category view
 */
class View extends \Magento\Framework\App\Action\Action
{
    /**
     * View blog category action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $category = $this->_objectManager->create('Magerubik\Blog\Model\Category')->load($id);
        if (!$category->getId()) {
            $this->_forward('index', 'noroute', 'cms');
            return;
        }
        $this->_objectManager->get('\Magento\Framework\Registry')->register('current_blog_category', $category);
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}