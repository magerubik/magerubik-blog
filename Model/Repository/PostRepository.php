<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Model\Repository;
use Magerubik\Blog\Api\Data\PostInterface;
use Magerubik\Blog\Api\PostRepositoryInterface;
use Magerubik\Blog\Model\PostFactory;
use Magerubik\Blog\Model\ResourceModel\Post as PostsResource;
use Magerubik\Blog\Model\ResourceModel\Post\CollectionFactory;
use Magerubik\All\Model\Source\Status as PostStatus;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;
/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class PostRepository implements PostRepositoryInterface
{
    /**
     * @var PostFactory
     */
    private $postFactory;
    /**
     * @var PostsResource
     */
    private $postResource;
    /**
     * Model data storage
     *
     * @var array
     */
    private $posts;
    /**
     * @var CollectionFactory
     */
    private $postCollectionFactory;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManagerInterface;
    public function __construct(
        PostFactory $postFactory,
        PostsResource $postResource,
        CollectionFactory $postCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManagerInterface
    ) {
        $this->postFactory = $postFactory;
        $this->postResource = $postResource;
        $this->postCollectionFactory = $postCollectionFactory;
        $this->storeManagerInterface = $storeManagerInterface;
    }
    /**
     * @inheritdoc
     */
    public function save(PostInterface $post)
    {
        try {
            if ($post->getPostId()) {
                $post = $this->getById($post->getPostId())->addData($post->getData());
            } else {
                $post->setPostId(null);
            }
            $this->postResource->save($post);
            unset($this->posts[$post->getPostId()]);
        } catch (\Exception $e) {
            if ($post->getPostId()) {
                throw new CouldNotSaveException(
                    __(
                        'Unable to save post with ID %1. Error: %2',
                        [$post->getPostId(), $e->getMessage()]
                    )
                );
            }
            throw new CouldNotSaveException(__('Unable to save new post. Error: %1', $e->getMessage()));
        }
        return $post;
    }
    /**
     * @return \Magerubik\Blog\Model\Post
     */
    public function getPost()
    {
        return $this->postFactory->create();
    }
    /**
     * @param int $postId
     * @return PostInterface|mixed
     * @throws NoSuchEntityException
     */
    public function getById($postId)
    {
        if (!isset($this->posts[$postId])) {
            /** @var \Magerubik\Blog\Model\Post $posts */
            $posts = $this->postFactory->create();
            $this->postResource->load($posts, $postId);
            if (!$posts->getPostId()) {
                throw new NoSuchEntityException(__('Posts with specified ID "%1" not found.', $postId));
            }
            $this->posts[$postId] = $posts;
        }
        return $this->posts[$postId];
    }
    /**
     * @param $urlKey
     * @return \Magento\Framework\DataObject
     * @throws NoSuchEntityException
     */
    public function getByUrlKey($urlKey)
    {
        $collection = $this->postCollectionFactory->create();
        $collection->addFieldToFilter('identifier', $urlKey)
            ->addFieldToFilter(
                'is_active',
                [
                    'in' => [
                        PostStatus::STATUS_ENABLED,
                        PostStatus::STATUS_DISABLED
                    ]
                ]
            )
            ->setUrlKeyIsNotNull();
        $this->addStoreFilter($collection);
        $collection->setLimit(1);
        return $collection->getFirstItem();
    }
    /**
     * @param $urlKey
     * @return \Magento\Framework\DataObject
     * @throws NoSuchEntityException
     */
    public function getByUrlKeyWithAllStatuses($urlKey)
    {
        $collection = $this->postCollectionFactory->create();
        $collection->addFieldToFilter('identifier', $urlKey)->setUrlKeyIsNotNull();
        $collection->setLimit(1);
        return $collection->getFirstItem();
    }
    /**
     * @param PostInterface $post
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(PostInterface $post)
    {
        try {
            $this->postResource->delete($post);
            unset($this->posts[$post->getPostId()]);
        } catch (\Exception $e) {
            if ($post->getPostId()) {
                throw new CouldNotDeleteException(
                    __(
                        'Unable to remove posts with ID %1. Error: %2',
                        [$post->getPostId(), $e->getMessage()]
                    )
                );
            }
            throw new CouldNotDeleteException(__('Unable to remove posts. Error: %1', $e->getMessage()));
        }
        return true;
    }
    /**
     * @param int $postId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($postId)
    {
        $postsModel = $this->getById($postId);
        $this->delete($postsModel);
        return true;
    }
    /**
     * @param int $tagId
     * @return PostsResource\Collection
     */
    public function getTaggedPosts($tagId)
    {
        return $this->postCollectionFactory->create()->addTagFilter($tagId);
    }
    /**
     * @return PostsResource\Collection
     */
    public function getPostCollection()
    {
        return $this->postCollectionFactory->create();
    }
    /**
     * @return PostsResource\Collection
     * @throws NoSuchEntityException
     */
    public function getRecentPosts()
    {
        $collection = $this->getActivePosts();
        $collection->setUrlKeyIsNotNull();
        $collection->setDateOrder();
        return $collection;
    }
    /**
     * @param PostsResource\Collection $collection
     * @param null $storeId
     * @throws NoSuchEntityException
     */
    private function addStoreFilter($collection, $storeId = null)
    {
        if (!$this->storeManagerInterface->isSingleStoreMode()) {
            $collection->addStoreFilter($storeId ?: $this->storeManagerInterface->getStore()->getId());
        }
    }
    /**
     * @param null $storeId
     * @return PostsResource\Collection
     * @throws NoSuchEntityException
     */
    public function getActivePosts($storeId = null)
    {
        $posts = $this->postCollectionFactory->create();
        $this->addStoreFilter($posts, $storeId);
        $posts->addFieldToFilter('is_active', PostStatus::STATUS_ENABLED);
        return $posts;
    }
    /**
     * @param null $storeId
     * @return PostsResource\Collection
     * @throws NoSuchEntityException
     */
    public function getFeaturedPosts($storeId = null)
    {
        $collection = $this->getActivePosts($storeId);
        $collection->addFieldToFilter(PostInterface::IS_FEATURED, 1)
            ->addFieldToFilter('thumbnailimage', ['neq' => 'NULL']);
        return $collection;
    }
}