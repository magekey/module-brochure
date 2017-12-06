<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Model\ResourceModel\Item\Group;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use MageKey\Brochure\Model\ResourceModel\Item\Group as ResourceModel;

class ReadHandler implements ExtensionInterface
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
        if ($object->getId()) {
            $ids = $this->resource->getRecordsColumn((int)$object->getId(), static::RELATION_FIELD);
            $object->setData(sprintf('%ss', static::RELATION_FIELD), $ids);
        }
        return $object;
    }
}
