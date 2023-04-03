<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Model\DataProvider;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magerubik\All\Model\ImageProcessor;
use Magerubik\Blog\Model\Post;
use Magerubik\Blog\Api\PostRepositoryInterface;
use Magerubik\Blog\Api\Data\PostInterface;
use Magerubik\Blog\Model\ResourceModel\Post\CollectionFactory;
class PostDataProvider extends AbstractDataProvider
{
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;
    /**
     * @var PostRepositoryInterface
     */
    private $postRepository;
    /**
     * @var ImageProcessor
     */
    private $imageProcessor;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        DataPersistorInterface $dataPersistor,
        CollectionFactory $collectionFactory,
        PostRepositoryInterface $postRepository,
        ImageProcessor $imageProcessor,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->postRepository = $postRepository;
        $this->imageProcessor = $imageProcessor;
        $this->collectionFactory = $collectionFactory;
    }
    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getData()
    {
        $data = parent::getData();
        if ($data['totalRecords'] > 0) {
            $postId = (int)$data['items'][0]['post_id'];
            $model = $this->getPost($postId);
            if ($model) {
                $postData = $model->getData();
                $postData = $this->modifyData($model, $postData);
                $data[$postId] = $postData;
            }
        }
        if ($savedData = $this->dataPersistor->get(Post::CURRENT_NAME)) {
            $savedPostId = isset($savedData['post_id']) ? $savedData['post_id'] : null;
            $data[$savedPostId] = isset($data[$savedPostId])
                ? array_merge($data[$savedPostId], $savedData)
                : $savedData;
            $this->dataPersistor->clear(Post::CURRENT_NAME);
        }
        return $data;
    }
    /**
     * @param $postId
     * @return PostInterface|null
     */
    private function getPost($postId)
    {
        try {
            $model = $this->postRepository->getById($postId);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $model = null;
        }
        return $model;
    }
    /**
     * @param \Magerubik\Blog\Api\Data\PostInterface $model
     * @param array $postData
     * @return array
     */
    protected function modifyData(\Magerubik\Blog\Api\Data\PostInterface $model, $postData)
    {
        $this->imageFormat($model->getThumbnailimage(), $postData, Post::THUMBNAILIMAGE);
        $this->imageFormat($model->getDetailimage(), $postData, Post::DETAILIMAGE);
        if (isset($postData['related_post_ids']) && $postData['related_post_ids']) {
            $postData['related_post_ids'] = [
                'related_post_container' => array_values($this->getPostsData($postData['related_post_ids']))
            ];
        }
		if (isset($postData['related_product_ids']) && $postData['related_product_ids']) {
			$collection = $model->getRelatedProducts()->addAttributeToSelect('name');
			$items = [];
            foreach ($collection as $item) {
				$itemData = $item->getData();
				$itemData['id'] = $item->getId();
				foreach ($itemData as $key => $value) {
                    if (!in_array($key, ['entity_id','name','id'])) {
                        unset($itemData[$key]);
                    }
                }
				$items[] = $itemData;
			}
            $postData['related_product_ids']['related_product_container'] = $items;
        }
        return $postData;
    }
    /**
     * @param $thumbnail
     * @param $categoryData
     * @param $fieldName
     */
    protected function imageFormat($thumbnail, &$categoryData, $fieldName)
    {
		$thumbnailSrc = $thumbnail;
		if(is_array($thumbnail)){ $thumbnailSrc = $thumbnail[0]['name'];}
		if ($thumbnailSrc) {
            $categoryData[$fieldName] = [
                [
                    'name' => $thumbnail,
                    'url'  => $this->imageProcessor->getThumbnailUrl($thumbnailSrc, 'magerubik/blog/')
                ]
            ];
        }
    }
    /**
     * @param array|string $postIds
     * @return array
     */
    protected function getPostsData($postIds)
    {
        if (!is_array($postIds)) {
            $postIds = explode(',', $postIds);
        }
        $postCollection = $this->collectionFactory->create();
        $postCollection->addFieldToFilter('post_id', ['in' => $postIds]);
        $result = [];
        /** @var PostInterface $post */
        foreach ($postCollection->getItems() as $post) {
            $result[$post->getPostId()] = $this->fillData($post);
        }
        return $result;
    }
    /**
     * @param PostInterface $post
     *
     * @return array
     */
    protected function fillData(PostInterface $post)
    {
        return [
            'post_id'        => $post->getPostId(),
            'thumbnailimage' => $post->getThumbnailimageSrc(),
            'title'          => $post->getTitle(),
            'identifier'        => $post->getIdentifier(),
            'is_active'         => $post->getIsActive()
        ];
    }
}