<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Controller\Adminhtml\Import;
/**
 * Blog prepare wordpress import controller
 */
class Wordpress extends \Magento\Backend\App\Action
{
	/**
     * Prepare wordpress import
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu('Magerubik_Blog::import');
        $title = __('Blog Import from WordPress (beta)');
        $this->_view->getPage()->getConfig()->getTitle()->prepend($title);
        $this->_addBreadcrumb($title, $title);
        $config = new \Magento\Framework\DataObject(
            (array)$this->_getSession()->getData('import_wordpress_form_data', true) ?: array()
        );
        $this->_objectManager->get('\Magento\Framework\Registry')->register('import_config', $config);
        $this->_view->renderLayout();
    }
}