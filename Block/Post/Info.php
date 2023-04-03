<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Block\Post;
use Magento\Store\Model\ScopeInterface;
/**
 * Blog post info block
 */
class Info extends \Magento\Framework\View\Element\Template
{
	/**
     * Block template file
     * @var string
     */
    protected $_template = 'post/info.phtml';
    /**
     * Retrieve formated posted date
     * @var string
     * @return string
     */
    public function getPostedOn($format = 'Y-m-d H:i:s')
    {
        return date($format, strtotime($this->getPost()->getData('publish_time')));
    }
}