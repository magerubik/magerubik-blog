<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Api;
use Magento\Framework\Exception\NoSuchEntityException;
use Magerubik\Blog\Model\ResourceModel\Post as PostResource;
/**
 * @api
 */
interface PostRepositoryInterface
{
    /**
     * Save
     * @param \Magerubik\Blog\Api\Data\PostInterface $post
     */
    public function save(\Magerubik\Blog\Api\Data\PostInterface $post);
    /**
     * Get by id
     */
    public function getById($postId);
    /**
     * @param $urlKey
     * @return \Magerubik\Blog\Api\Data\PostInterface
     */
    public function getByUrlKey($urlKey);
    /**
     * @param $urlKey
     * @return \Magerubik\Blog\Api\Data\PostInterface
     */
    public function getByUrlKeyWithAllStatuses($urlKey);
    /**
     * @return \Magerubik\Blog\Api\Data\PostInterface
     */
    public function getPost();
    /**
     * Delete
     *
     * @param \Magerubik\Blog\Api\Data\PostInterface $post
     *
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Magerubik\Blog\Api\Data\PostInterface $post);
    /**
     * Delete by id
     * @param int $postId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($postId);
    /**
     * @return \Magerubik\Blog\Model\ResourceModel\Post\Collection;
     */
    public function getPostCollection();
    /**
     * @return PostsResource\Collection
     * @throws NoSuchEntityException
     */
    public function getRecentPosts();
    /**
     * @param null $storeId
     * @return \Magerubik\Blog\Model\ResourceModel\Post\Collection
     */
    public function getActivePosts($storeId = null);
}