<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Blog
 */
namespace Magerubik\Blog\Ui\Component\Listing\Filters;
use Magento\Framework\Api\Search\SearchResultInterface;
/**
 * Class
 */
class DataProvider extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{
    /**
     * @param SearchResultInterface $searchResult
     *
     * @return array
     */
    protected function searchResultToOutput(SearchResultInterface $searchResult)
    {
        $arrItems = [];
        $arrItems['items'] = [];
        $this->applyFilters($searchResult);
        foreach ($searchResult->getItems() as $item) {
            $data = $item->getData();
            if (isset($data['stores']) && $data['stores'] == '0') {
                $data['stores'] = ['0'];
            }
            $arrItems['items'][] = $data;
        }
        $arrItems['totalRecords'] = $searchResult->getSize();
        return $arrItems;
    }
    protected function applyFilters(SearchResultInterface $searchResult)
    {
        $tag = (int)$this->request->getParam('tag_id');
        if ($tag) {
            $searchResult->addTagFilter($tag);
        } else {
            $category = (int)$this->request->getParam('category_id');
            if ($category) {
                $searchResult->addCategoryFilter([$category]);
            } else {
                $author = (int)$this->request->getParam('author_id');
                if ($category) {
					$searchResult->addAuthorFilter([$author]);
				}
            }
        }
    }
}