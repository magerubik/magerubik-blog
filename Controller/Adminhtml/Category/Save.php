<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Controller\Adminhtml\Category;
use Magerubik\Blog\Api\CategoryRepositoryInterface;
use Magerubik\Blog\Block\Sidebar\Category\TreeRenderer;
use Magerubik\Blog\Model\Category;
use Magerubik\Blog\Api\Data\CategoryInterface;
use Magerubik\All\Controller\Adminhtml\Base\SaveBase;
/**
 * Blog category save controller
 */
class Save extends \Magerubik\Blog\Controller\Adminhtml\Category
{
	use SaveBase;
	public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = (int)$this->getRequest()->getParam(CategoryInterface::CATEGORY_ID);
            try {
                $inputFilter = new \Zend_Filter_Input([], [], $data);
                $data = $inputFilter->getUnescaped();
                if ($id) {
                    $model = $this->getCategoryRepository()->getById($id);
                } else {
                    $model = $this->getCategoryRepository()->getCategory();
                }
                $model->addData($data);
                if ($this->checkIdentifier($model->getUrlKey(), $model->getCategoryId())) {
                    $this->getDataPersistor()->set(Category::PERSISTENT_NAME, $data);
                    $this->getMessageManager()->addErrorMessage(__('Category with the same url key already exists'));
                    $this->addRedirect($id);
                    return;
                }
                $this->_getSession()->setPageData($model->getData());
                $this->getCategoryRepository()->save($model);
                $this->getMessageManager()->addSuccessMessage(__('You saved the item.'));
                $this->_getSession()->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['id' => $model->getCategoryId()]);
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                $this->getMessageManager()->addErrorMessage($e->getMessage());
                $this->getDataPersistor()->set(Category::PERSISTENT_NAME, $data);
                $this->addRedirect($id);
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->getMessageManager()->addErrorMessage($e->getMessage());
                $this->_getSession()->setPageData(null);
                $this->getDataPersistor()->set(Category::PERSISTENT_NAME, $data);
                $this->addRedirect($id);
                return;
            } catch (\Exception $e) {
                $this->getMessageManager()->addErrorMessage(
                    __('Something went wrong while saving the item data. Please review the error log.')
                );
                $this->getLogger()->critical($e);
                $this->_getSession()->setPageData($data);
                $this->_redirect('*/*/edit', ['category_id' => $id]);
                return;
            }
        }
        $this->_redirect('*/*/');
    }
}