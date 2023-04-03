<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Controller\Adminhtml\Post;
/**
 * Blog post MassStatus controller
 */
class MassStatus extends \Magerubik\Blog\Controller\Adminhtml\Post\AbstractMassAction
{
	/**
     * @param $post
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    protected function itemAction($post)
    {
        try {
            $status = $this->getRequest()->getParam('status');
			$post->setIsActive($status)->save();
        } catch (\Exception $e) {
            $this->getMessageManager()->addErrorMessage($e->getMessage());
        }

        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
}