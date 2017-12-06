<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Ui\DataProvider\Form\Modifier\Item;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;

use MageKey\Brochure\Ui\DataProvider\Form\Modifier\AbstractModifier;
use MageKey\Brochure\Controller\Adminhtml\Item as AbstractController;
use MageKey\Brochure\Model\ResourceModel\Item as ResourceItem;
use MageKey\Brochure\Helper\File as FileHelper;
use MageKey\Brochure\Helper\Data as DataHelper;

class General extends AbstractModifier
{
    /**
     * Fields
     */
    const FIELD_ID = 'item_id';

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var ResourceItem
     */
    protected $resourceItem;

    /**
     * @var FileHelper
     */
    protected $fileHelper;

    /**
     * @param DataPersistorInterface $dataPersistor
     * @param ResourceItem $resourceItem
     * @param FileHelper $fileHelper
     */
    public function __construct(
        DataPersistorInterface $dataPersistor,
        ResourceItem $resourceItem,
        FileHelper $fileHelper
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->resourceItem = $resourceItem;
        $this->fileHelper = $fileHelper;
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
     * Resolve data persistence
     *
     * @param array $data
     * @return array
     */
    private function resolvePersistentData(array $data)
    {
        $persistentData = (array)$this->dataPersistor->get(AbstractController::UI_DATA_PERSISTOR);
        $this->dataPersistor->clear(AbstractController::UI_DATA_PERSISTOR);
        foreach ([
            'image' => DataHelper::COVER_UPLOAD_DIR,
            'file' => DataHelper::ITEM_UPLOAD_DIR
        ] as $key => $dir) {
            if (!empty($persistentData[$key])) {
                $persistentData[$key] = $this->getImageArray($dir, $persistentData[$key]);
            }
        }
        $data[$persistentData[static::FIELD_ID]] = $persistentData;
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyMeta(array $meta)
    {
        $meta = array_replace_recursive(
            $meta,
            [
                'general' => [
                    'children' => [
                        'position' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'default' => $this->getDefaultItemPosition()
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );
        return $meta;
    }

    /**
     * Retrieve default item position
     *
     * @return int
     */
    protected function getDefaultItemPosition()
    {
        $connection = $this->resourceItem->getConnection();
        $select = $connection->select()
            ->from(
                $this->resourceItem->getMainTable(),
                ['max' => new \Zend_Db_Expr('MAX(position)')]
            );
        $lastNumber = (int)$connection->fetchOne($select);
        return ($lastNumber + 5);
    }

    /**
     * Retrieve image array
     *
     * @param string $dir
     * @param string $image
     * @return array
     */
    protected function getImageArray($dir, $image)
    {
        $path = $dir . '/' . $image;
        return [
            0 => [
                'file' => $image,
                'url' => $this->fileHelper->getMediaUrl($path),
                'size' => $this->fileHelper->getFileSize($path),
                'name' => basename($image),
                'type' => $this->fileHelper->getMimeType($path),
            ]
        ];
    }
}
