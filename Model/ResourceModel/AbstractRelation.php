<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Model\ResourceModel;

abstract class AbstractRelation extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Get records
     *
     * @param int $id
     * @return array
     */
    public function getRecords($id)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable())
            ->where($this->getIdFieldName() . ' = ?', $id);
        return $connection->fetchAll($select);
    }

    /**
     * Get records column
     *
     * @param int $id
     * @param string $column
     * @return array
     */
    public function getRecordsColumn($id, $column)
    {
        $records = $this->getRecords($id);
        $data = [];
        foreach ($records as $record) {
            $data[] = $record[$column];
        }
        return $data;
    }

    /**
     * Insert record
     *
     * @param int $id
     * @param array $data
     * @return int
     */
    public function insertRecords($id, array $data)
    {
        $fieldId = $this->getIdFieldName();
        foreach ($data as & $item) {
            $item[$fieldId] = $id;
        }
        return $this->getConnection()->insertMultiple($this->getMainTable(), $data);
    }

    /**
     * Delete records
     *
     * @param int $id
     * @return int
     */
    public function deleteRecords($id)
    {
        return $this->getConnection()->delete(
            $this->getMainTable(),
            [$this->getIdFieldName() . ' = ?' => (int)$id]
        );
    }
}
