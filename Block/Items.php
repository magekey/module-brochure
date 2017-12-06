<?php
/**
 * Copyright © MageKey. All rights reserved.
 */

namespace MageKey\Brochure\Block;

use MageKey\Brochure\Model\ResourceModel\Item\Collection as ItemCollection;
use MageKey\Brochure\Model\ResourceModel\Item\CollectionFactory as ItemCollectionFactory;

class Items extends \Magento\Framework\View\Element\Template
{
    /**
     * @var string
     */
    protected $_template = 'MageKey_Brochure::item/list.phtml';

    /**
     * @var ItemCollectionFactory
     */
    protected $itemCollectionFactory;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param ItemCollectionFactory $itemCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        ItemCollectionFactory $itemCollectionFactory,
        array $data = []
    ) {
        $this->itemCollectionFactory = $itemCollectionFactory;
        parent::__construct(
            $context,
            $data
        );
    }

    /**
     * Get value of widgets' group parameter
     *
     * @return int
     */
    public function getGroupId()
    {
        return (int)$this->getData('group_id');
    }

    /**
     * Retrieve how many items should be displayed
     *
     * @return int
     */
    public function getCount()
    {
        return (int)$this->getData('count');
    }

    /**
     * Retrieve item collection
     *
     * @return ItemCollection
     */
    public function getCollection()
    {
        if (!$this->hasCollection()) {
            $collection = $this->itemCollectionFactory->create();
            $collection->filterByGroupId($this->getGroupId());
            if ($limit = $this->getCount()) {
                $collection->setPageSize($limit);
            }
            $this->setData('collection', $collection);
        }
        return $this->getData('collection');
    }
}
