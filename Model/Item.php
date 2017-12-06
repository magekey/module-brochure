<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Model;

use MageKey\Brochure\Api\Data\ItemInterface as DataInterface;
use MageKey\Brochure\Model\ResourceModel\Item as ResourceModel;

class Item extends \Magento\Framework\Model\AbstractModel implements DataInterface
{
    /**
     * Cache tag
     */
    const CACHE_TAG = 'mgk_brochure_item';

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
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * {@inheritdoc}
     */
    public function getImage()
    {
        return $this->getData(self::IMAGE);
    }

    /**
     * {@inheritdoc}
     */
    public function getFile()
    {
        return $this->getData(self::FILE);
    }

    /**
     * {@inheritdoc}
     */
    public function getPosition()
    {
        return $this->getData(self::POSITION);
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * {@inheritdoc}
     */
    public function setImage($image)
    {
        return $this->setData(self::IMAGE, $image);
    }

    /**
     * {@inheritdoc}
     */
    public function setFile($file)
    {
        return $this->setData(self::FILE, $file);
    }

    /**
     * {@inheritdoc}
     */
    public function setPosition($position)
    {
        return $this->setData(self::POSITION, (int)$position);
    }
}
