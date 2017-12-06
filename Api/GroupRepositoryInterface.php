<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Api;

use MageKey\Brochure\Api\Data\GroupInterface as DataInterface;

/**
 * CRUD interface.
 * @api
 */
interface GroupRepositoryInterface
{
    /**
     * Save object.
     *
     * @param DataInterface $object
     * @return DataInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(DataInterface $object);

    /**
     * Retrieve object.
     *
     * @param int $objectId
     * @return DataInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($objectId);

    /**
     * Delete object.
     *
     * @param DataInterface $object
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(DataInterface $object);

    /**
     * Delete object by ID.
     *
     * @param int $objectId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($objectId);
}
