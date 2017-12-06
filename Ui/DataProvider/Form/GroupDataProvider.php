<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Ui\DataProvider\Form;

use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;

use MageKey\Brochure\Model\ResourceModel\Group\Collection as DataCollection;
use MageKey\Brochure\Model\ResourceModel\Group\CollectionFactory as DataCollectionFactory;

/**
 * Class DataProvider
 */
class GroupDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var DataCollection
     */
    protected $collection;

    /**
     * @var PoolInterface
     */
    private $pool;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param DataCollectionFactory $dataCollectionFactory
     * @param PoolInterface $pool
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        DataCollectionFactory $dataCollectionFactory,
        PoolInterface $pool,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $dataCollectionFactory->create();
        $this->pool = $pool;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        /** @var ModifierInterface $modifier */
        foreach ($this->pool->getModifiersInstances() as $modifier) {
            $this->data = $modifier->modifyData($this->data);
        }

        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta()
    {
        $meta = parent::getMeta();

        /** @var ModifierInterface $modifier */
        foreach ($this->pool->getModifiersInstances() as $modifier) {
            $meta = $modifier->modifyMeta($meta);
        }

        return $meta;
    }
}
