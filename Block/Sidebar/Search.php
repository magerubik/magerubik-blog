<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Block\Sidebar;
/**
 * Blog sidebar categories block
 */
class Search extends  \Magento\Framework\View\Element\Template
{
	use Widget;
	/**
     * @var string
     */
    protected $_widgetKey = 'search';
	/**
	 * Retrieve query
	 * @return string
	 */
	public function getQuery()
	{
		return urldecode($this->getRequest()->getParam('q', ''));
	}
}