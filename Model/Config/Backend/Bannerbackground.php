<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Model\Config\Backend;
class Bannerbackground extends \Magento\Config\Model\Config\Backend\Image
{
    const UPLOAD_DIR = 'magerubik/bannerbackground';   
    protected function _getUploadDir()
    {
        return $this->_mediaDirectory->getAbsolutePath($this->_appendScopeInfo(self::UPLOAD_DIR));
    }
    protected function _addWhetherScopeInfo()
    {
        return true;
    }
    protected function _getAllowedExtensions()
    {
        return ['jpg', 'jpeg', 'png'];
    }
}