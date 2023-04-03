<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
declare(strict_types=1);
namespace Magerubik\Blog\Controller\Adminhtml\Post;
use Magerubik\Blog\Model\Post;
use Magerubik\All\Model\Source\Status as PostStatus;
use Magerubik\All\Controller\Adminhtml\Base\SaveBase;
use Magerubik\Blog\Api\PostRepositoryInterface;
class Save extends \Magerubik\Blog\Controller\Adminhtml\Post
{
    use SaveBase;
	/**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = (int)$this->getRequest()->getParam('post_id');
            try {
                $inputFilter = new \Zend_Filter_Input([], [], $data);
                $data = $inputFilter->getUnescaped();
                if ($id) {
                    $model = $this->getPostRepository()->getById($id);
                } else {
                    $model = $this->getPostRepository()->getPost();
                }
                $data = $this->prepareData($data);
                $model->addData($data);
                if ($this->checkIdentifier($model->getIdentifier(), $model->getPostId())) {
                    $this->getDataPersistor()->set(Post::CURRENT_NAME, $data);
                    $this->getMessageManager()->addErrorMessage(__('Post with the same url key already exists'));
                    $this->addRedirect($id);
                    return;
                }
                $this->_getSession()->setPageData($data);
                $this->prepareForSave($model);
                $this->getPostRepository()->save($model);
                $this->getMessageManager()->addSuccessMessage(__('You saved the item.'));
                $this->_getSession()->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['id' => $model->getPostId()]);
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                $this->getMessageManager()->addErrorMessage($e->getMessage());
                $this->getDataPersistor()->set(Post::CURRENT_NAME, $data);
                $this->addRedirect($id);
                return;
            } catch (\Exception $e) {
                $this->getMessageManager()->addErrorMessage(
                    __('Something went wrong while saving the item data. Please review the error log.')
                );
                $this->getLogger()->critical($e);
                $this->_getSession()->setPageData($data);
                $this->_redirect('*/*/edit', ['id' => $id]);
                return;
            }
        }
        $this->_redirect('*/*/');
    }
    /**
     * @param array $data
     * @return array
     */
    protected function prepareData(array $data)
    {
        if (!isset($data['categories'])) {
            $data['categories'] = [];
        }
        $data['related_post_container'] = $data['related_post_container'] ?? [];
        if (is_array($data['related_post_container'])) {
            $related = [];
            foreach ($data['related_post_container'] as $item) {
                $related[] = $item['post_id'];
            }
            $related = implode(',', $related);
            $data['related_post_ids'] = $related;
            unset($data['related_post_container']);
        }
		$data['related_product_container'] = $data['related_product_container'] ?? [];
        if (is_array($data['related_product_container'])) {
            $related = [];
            foreach ($data['related_product_container'] as $item) {
                $related[] = $item['entity_id'];
            }
            $related = implode(',', $related);
            $data['related_product_ids'] = $related;
            unset($data['related_product_container']);
        }
        return $data;
    }
    /**
     * @param $model
     */
    private function prepareForSave($model)
    {
		$this->prepareImage($model, 'detailimage');
        $this->prepareImage($model, 'thumbnailimage');
        $this->prepareStatus($model);
    }
    public function prepareStatus($model)
    {
        $currentTimestamp = $this->getTimezone()->date()->getTimestamp();
        $publishedDate = strtotime($model->getPublishTime());
        if (in_array($model->getIsActive(), [PostStatus::STATUS_DISABLED, PostStatus::STATUS_ENABLED])) {
			$status = $publishedDate > $currentTimestamp ? PostStatus::STATUS_DISABLED : PostStatus::STATUS_ENABLED;
			$model->setIsActive($status);
        }
        $publishedDate = $publishedDate ?: $currentTimestamp;
        $model->setPublishTime($publishedDate);
    }
    /**
     * @param $model
     * @param $imageName
     */
    private function prepareImage($model, $imageName)
    {
        $fileName = $imageName;
        $thumbnail = $model->getData($fileName);
		if (isset($thumbnail) && is_array($thumbnail)) {
			$imagePath = $this->getImageProcessor()->MrMoveFile($thumbnail[0]['name'],'magerubik/blog/');
			$model->unsetData($imageName, $imagePath);
            if ($imagePath !== null) {
				$model->setData($imageName, $imagePath);
            }
        } else {
			$model->setData($imageName, null);
        }
    }
	protected function getRepository(): PostRepositoryInterface
    {
        return $this->getPostRepository();
    }
}