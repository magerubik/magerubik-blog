<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Controller\Adminhtml\Category;
/**
 * Blog category create new controller
 */
class NewAction extends \Magerubik\Blog\Controller\Adminhtml\Category
{
	public function execute()
    {
        $this->_forward('edit');
    }
}