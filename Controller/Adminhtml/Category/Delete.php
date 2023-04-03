<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Controller\Adminhtml\Category;
/**
 * Blog category delete controller
 */
class Delete extends \Magerubik\Blog\Controller\Adminhtml\Category
{
	/**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = (int)$this->getRequest()->getParam('id');
        if ($id) {
            try {
                $this->getCategoryRepository()->deleteById($id);
                $this->getMessageManager()->addSuccessMessage(__('You have deleted the category.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->getMessageManager()->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->getMessageManager()->addErrorMessage(__('We can\'t find a category to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}