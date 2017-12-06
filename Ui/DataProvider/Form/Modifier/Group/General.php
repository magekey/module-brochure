<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Ui\DataProvider\Form\Modifier\Group;

use Magento\Framework\App\Request\DataPersistorInterface;

use MageKey\Brochure\Ui\DataProvider\Form\Modifier\AbstractModifier;
use MageKey\Brochure\Controller\Adminhtml\Group as AbstractController;
use MageKey\Brochure\Model\ResourceModel\Group as ResourceFilter;

class General extends AbstractModifier
{
    /**
     * Fields
     */
    const FIELD_ID = 'group_id';

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var ResourceFilter
     */
    protected $resourceFilter;

    /**
     * @param DataPersistorInterface $dataPersistor
     * @param ResourceFilter $resourceFilter
     */
    public function __construct(
        DataPersistorInterface $dataPersistor,
        ResourceFilter $resourceFilter
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->resourceFilter = $resourceFilter;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        if ($this->dataPersistor->get(AbstractController::UI_DATA_PERSISTOR)) {
            return $this->resolvePersistentData($data);
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyMeta(array $meta)
    {
        return $meta;
    }

    /**
     * Resolve data persistence
     *
     * @param array $data
     * @return array
     */
    private function resolvePersistentData(array $data)
    {
        $persistentData = (array)$this->dataPersistor->get(AbstractController::UI_DATA_PERSISTOR);
        $this->dataPersistor->clear(AbstractController::UI_DATA_PERSISTOR);
        $data[$persistentData[static::FIELD_ID]] = $persistentData;

        return $data;
    }
}
