<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Controller\Adminhtml\Uploader;
use Magento\Framework\Controller\ResultFactory;
/**
 * Class to show swatch image and save it on disk
 */
class Image extends \Magento\Backend\App\Action
{
    /**
     * @var \Magerubik\All\Model\ImageUploader
     */
    private $imageUploader;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magerubik\All\Model\ImageUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }
    /**
     * Upload file controller action.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $imageField = '';
            foreach ($this->getRequest()->getFiles() as $key => $file) {
                $imageField = $key;
                break;
            }
            $result = $this->imageUploader->saveMagerubikFileToTmpDir($imageField, 'magerubik/blog/tmp');
            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        $this->getResponse()->setBody(json_encode($result));
    }
}