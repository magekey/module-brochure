<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Model\ResourceModel\Item;

use MageKey\Brochure\Model\ResourceModel\AbstractRelation;

class Group extends AbstractRelation
{
    /**
     * @var array
     */
    protected $_uniqueFields = [
        [
            'field' => ['item_id', 'group_id'],
            'title' => 'Item and Group',
        ]
    ];

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mgk_brochure_item_group', 'item_id');
    }
}
