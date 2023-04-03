<?php
/**
 * @author Magerubik Team
 * @copyright Copyright (c) 2020 Magerubik (https://www.magerubik.com)
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Helper;
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_labels = null;
    protected $_themeCfg = array();
    public function getConfig($cfg=null)
    {
        if($cfg) return $this->scopeConfig->getValue( $cfg, \Magento\Store\Model\ScopeInterface::SCOPE_STORE );
        return 'dev';
    }
}
