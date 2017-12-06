<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;

use MageKey\Brochure\Api\ItemRepositoryInterface as RepositoryInterface;
use MageKey\Brochure\Api\Data\ItemInterface as DataInterface;
use MageKey\Brochure\Model\ResourceModel\Item as ResourceModel;
use MageKey\Brochure\Model\ItemFactory as ModelFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ItemRepository implements RepositoryInterface
{
    /**
     * Event Prefix
     */
    const EVENT_PREFIX = 'mgk_brochure_item';

    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * @var ResourceModel
     */
    protected $resource;

    /**
     * @var ModelFactory
     */
    protected $modelFactory;

    /**
     * @param EventManagerInterface $eventManager
     * @param ResourceModel $resource
     * @param ModelFactory $modelFactory
     */
    public function __construct(
        EventManagerInterface $eventManager,
        ResourceModel $resource,
        ModelFactory $modelFactory
    ) {
        $this->eventManager = $eventManager;
        $this->resource = $resource;
        $this->modelFactory = $modelFactory;
    }

    /**
     * Save object
     *
     * @param DataInterface $object
     * @return DataInterface $object
     * @throws CouldNotSaveException
     */
    public function save(DataInterface $object)
    {
        try {
            $this->resource->save($object);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }

        $this->eventManager->dispatch(
            static::EVENT_PREFIX . '_api_save',
            ['object' => $object]
        );

        return $object;
    }

    /**
     * Load object by given object Identity
     *
     * @param string $objectId
     * @return DataInterface $object
     * @throws NoSuchEntityException
     */
    public function getById($objectId)
    {
        $object = $this->modelFactory->create();
        $this->resource->load($object, $objectId);
        if (!$object->getId()) {
            throw new NoSuchEntityException(__('Model instance with id "%1" does not exist.', $objectId));
        }

        return $object;
    }

    /**
     * Delete object
     *
     * @param DataInterface $object
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(DataInterface $object)
    {
        $entityId = $object->getId();
        try {
            $this->resource->delete($object);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__($e->getMessage()));
        }

        $this->eventManager->dispatch(
            static::EVENT_PREFIX . '_api_delete',
            ['object_id' => $entityId]
        );

        return true;
    }

    /**
     * Delete object by given object Identity
     *
     * @param string $objectId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($objectId)
    {
        return $this->delete($this->getById($objectId));
    }
}
