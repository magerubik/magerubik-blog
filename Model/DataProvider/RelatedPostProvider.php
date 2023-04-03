<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Model\DataProvider;
use Magerubik\Blog\Api\Data\PostInterface;
use Magerubik\Blog\Model\ResourceModel\Post\CollectionFactory;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Magento\Framework\App\RequestInterface;
use Magerubik\Blog\Model\Repository\PostRepository;
/**
 * Class RelatedPostProvider
 */
class RelatedPostProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var PostInterface
     */
    private $post;
    /**
     * @var \Magerubik\Blog\Model\Repository\PostRepository
     */
    private $postRepository;
    /**
     * @var RequestInterface
     */
    private $request;
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        PostRepository $postRepository,
        RequestInterface $request,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collectionFactory = $collectionFactory;
        $this->collection = $collectionFactory->create();
        $this->postRepository = $postRepository;
        $this->request = $request;
    }
    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        $collection = $this->getCollection();
        if ($this->getPost()) {
            $collection->addFieldToFilter(
                $collection->getIdFieldName(),
                ['nin' => [$this->getPost()->getPostId()]]
            );
        }
        $items = [];
        foreach ($collection->getItems() as $post) {
            $items[] = $this->fillData($post);
        }
        $data = [
            'totalRecords' => count($items),
            'items' => $items
        ];
        return $data;
    }
    /**
     * @param array $post
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
    /**
     * Retrieve post
     * @return PostInterface|null
     */
    protected function getPost()
    {
        if (null !== $this->post) {
            return $this->post;
        }
        if (!($id = $this->request->getParam('post_id'))) {
            return null;
        }
        return $this->post = $this->postRepository->getById($id);
    }
}