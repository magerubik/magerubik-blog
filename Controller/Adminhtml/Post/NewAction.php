<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Controller\Adminhtml\Post;
/**
 * Blog post create new controller
 */
class NewAction extends \Magerubik\Blog\Controller\Adminhtml\Post
{
	public function execute()
    {
        $this->_forward('edit');
    }
}