<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Model\ResourceModel\Item;

use MageKey\Brochure\Model\Item as Model;
use MageKey\Brochure\Model\ResourceModel\Item as ResourceModel;
use MageKey\Brochure\Model\ResourceModel\Item\Group as GroupResourceModel;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'item_id';

    /**
     * @var GroupResourceModel
     */
    protected $_groupResourceModel;

    /**
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param GroupResourceModel $groupResourceModel
     * @param \Magento\Framework\DB\Adapter\AdapterInterface $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        GroupResourceModel $groupResourceModel,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        $this->_groupResourceModel = $groupResourceModel;
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $connection,
            $resource
        );
    }

    /**
     * Init model for collection
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }

    /**
     * Returns option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->_toOptionArray('item_id', 'title');
    }

    /**
     * Perform operations after collection load
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        if ($this->getFlag('join_related_groups')) {
            $this->loadRelatedGroups();
        }
        return parent::_afterLoad();
    }

    /**
     * Add groups column to select
     *
     * @param int $groupId
     * @return void
     */
    public function filterByGroupId($groupId = null)
    {
        $connection = $this->getConnection();
        $select = $this->getSelect();
        $select->join(
            ['filter_group' => $this->_groupResourceModel->getMainTable()],
            'main_table.item_id = filter_group.item_id'
            . $connection->quoteInto(' AND filter_group.group_id = ?', $groupId),
            false
        );
        return $this;
    }

    /**
     * Load related stores
     *
     * @return void
     */
    public function loadRelatedGroups()
    {
        $relation = 'related_groups';
        if (!$this->getFlag($relation)) {
            $linkField = $this->getIdFieldName();
            $linkedIds = $this->getColumnValues($linkField);
            $relationResource = $this->_groupResourceModel;
            $relationField = 'group_id';
            if (count($linkedIds)) {
                $connection = $this->getConnection();
                $select = $connection->select()
                    ->from([$relation => $relationResource->getMainTable()])
                    ->where($relation . '.' . $linkField . ' IN (?)', $linkedIds);
                $result = $connection->fetchAll($select);
                if ($result) {
                    $relationData = [];
                    foreach ($result as $relationItem) {
                        $relationData[$relationItem[$linkField]][] = $relationItem[$relationField];
                    }
                    foreach ($this as $item) {
                        $linkedId = $item->getData($linkField);
                        if (!isset($relationData[$linkedId])) {
                            continue;
                        }
                        $item->setData(sprintf('%ss', $relationField), $relationData[$linkedId]);
                    }
                }
            }
            $this->setFlag($relation, true);
        }
    }
}
