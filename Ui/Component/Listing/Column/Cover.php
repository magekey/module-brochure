<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

use MageKey\Brochure\Helper\File as FileHelper;
use MageKey\Brochure\Helper\Data as DataHelper;

class Cover extends Column
{
    /**
     * Url path
     */
    const URL_PATH_EDIT = 'mgk_brochure/item/edit';

    /**
     * Fields
     */
    const FIELD_ID = 'item_id';

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var FileHelper
     */
    protected $fileHelper;

    /**
     * Constructor
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param FileHelper $fileHelper
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        FileHelper $fileHelper,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->fileHelper = $fileHelper;
        parent::__construct(
            $context,
            $uiComponentFactory,
            $components,
            $data
        );
    }

    /**
     * @param array $items
     * @return array
     */
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                if ($item[$fieldName]) {
                    $path = DataHelper::COVER_UPLOAD_DIR . '/' . $item[$fieldName];
                    $item[$fieldName . '_src'] = $this->fileHelper->getMediaUrl($path);
                    $item[$fieldName . '_alt'] = $item['title'];
                    $item[$fieldName . '_orig_src'] = $this->fileHelper->getMediaUrl($path);
                    $item[$fieldName . '_link'] = $this->urlBuilder->getUrl(
                        static::URL_PATH_EDIT,
                        ['id' => $item[static::FIELD_ID]]
                    );
                }
            }
        }

        return $dataSource;
    }
}
