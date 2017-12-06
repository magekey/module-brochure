<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Model\ResourceModel\Item\Group;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use MageKey\Brochure\Model\ResourceModel\Item\Group as ResourceModel;

class SaveHandler implements ExtensionInterface
{
    /**
     * Fields
     */
    const RELATION_FIELD = 'group_id';

    /**
     * @var ResourceModel
     */
    protected $resource;

    /**
     * @param ResourceModel $resource
     */
    public function __construct(
        ResourceModel $resource
    ) {
        $this->resource = $resource;
    }

    /**
     * @param object $object
     * @param array $arguments
     * @return object
     * @throws \Exception
     */
    public function execute($object, $arguments = [])
    {
        $this->resource->deleteRecords($object->getId());
        if ($ids = $object->getData(sprintf('%ss', static::RELATION_FIELD))) {
            $data = [];
            foreach ($ids as $id) {
                if (empty($id)) {
                    $data = [
                        [static::RELATION_FIELD => $id]
                    ];
                    break;
                }
                $data[] = [
                    static::RELATION_FIELD => $id
                ];
            }
            if (!empty($data)) {
                $this->resource->insertRecords($object->getId(), $data);
            }
        }

        return $object;
    }
}
