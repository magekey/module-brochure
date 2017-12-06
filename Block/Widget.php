<?php
/**
 * Copyright © MageKey. All rights reserved.
 */

namespace MageKey\Brochure\Block;

use Magento\Widget\Block\BlockInterface;

class Widget extends \Magento\Framework\View\Element\Template implements BlockInterface
{
    /**
     * @var string
     */
    protected $_template = 'MageKey_Brochure::widget.phtml';

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
     * Get value of widgets' title parameter
     *
     * @return mixed|string
     */
    public function getTitle()
    {
        return $this->getData('title');
    }

    /**
     * Retrieve how many items should be displayed
     *
     * @return int
     */
    public function getItemsCount()
    {
        return (int)$this->getData('items_count');
    }

    /**
     * Retrieve item block
     *
     * @return \Magento\Framework\View\Element\AbstractBlock
     */
    public function getItemBlock()
    {
        if (!$this->hasItemBlock()) {
            $itemBlock = $this->getLayout()->createBlock(Items::class, '', [
                'data' => [
                    'group_id' => $this->getGroupId(),
                    'count' => $this->getItemsCount()
                ]
            ]);
            $this->setData('item_block', $itemBlock);
        }
        return $this->getData('item_block');
    }

    /**
     * Check if items exists
     *
     * @return bool
     */
    public function hasItems()
    {
        $itemBlock = $this->getItemBlock();
        return (bool)$itemBlock->getCollection()->count();
    }

    /**
     * Retrieve items html
     *
     * @return string
     */
    public function getItemHtml()
    {
        return $this->getItemBlock()->toHtml();
    }

    /**
     * Get key pieces for caching block content
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        return [
            'MGK_BROCHURE_ITEM_LIST_WIDGET',
            $this->_storeManager->getStore()->getId(),
            $this->_design->getDesignTheme()->getId(),
            $this->getGroupId(),
            $this->getTitle(),
            $this->getItemsCount()
        ];
    }
}
