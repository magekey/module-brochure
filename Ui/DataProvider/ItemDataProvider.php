<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Ui\DataProvider;

use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\UrlInterface;

/**
 * Class DataProvider
 */
class ItemDataProvider extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{
    /**
     * @var bool
     */
    protected $_filteredByGroup;

    /**
     * @inheritdoc
     */
    public function getSearchResult()
    {
        $searchResult = parent::getSearchResult();
        $searchResult->setFlag('join_related_groups', true);
        if ($this->_filteredByGroup) {
            $searchResult->filterByGroupId(
                $this->_filteredByGroup
            );
        }
        return $searchResult;
    }

    /**
     * @inheritdoc
     */
    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        switch ($filter->getField()) {
            case 'item_id':
                $filter->setField('main_table.item_id');
                break;
            case 'group_ids':
                $this->_filteredByGroup = $filter->getValue();
                return $this;
            default:
                break;
        }
        return parent::addFilter($filter);
    }
}
