<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Model;

use MageKey\Brochure\Api\Data\GroupInterface as DataInterface;
use MageKey\Brochure\Model\ResourceModel\Group as ResourceModel;

class Group extends \Magento\Framework\Model\AbstractModel implements DataInterface
{
    /**
     * Cache tag
     */
    const CACHE_TAG = 'mgk_brochure_group';

    /**#@-*/
    /**
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = self::CACHE_TAG;

    /**
     * Init resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return $this->getData(self::LABEL);
    }

    /**
     * {@inheritdoc}
     */
    public function setLabel($label)
    {
        return $this->setData(self::LABEL, $label);
    }
}
