<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Controller\Adminhtml\Item;

use Magento\Framework\Controller\ResultFactory;

use MageKey\Brochure\Controller\Adminhtml\Item as AbstractController;
use MageKey\Brochure\Api\ItemRepositoryInterface;
use MageKey\Brochure\Model\File\UploaderFactory;
use MageKey\Brochure\Helper\Data as HelperData;

class Upload extends AbstractController
{
    /**
     * @var UploaderFactory
     */
    protected $uploaderFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param ItemRepositoryInterface $itemRepository
     * @param UploaderFactory $uploaderFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        ItemRepositoryInterface $itemRepository,
        UploaderFactory $uploaderFactory
    ) {
        $this->uploaderFactory = $uploaderFactory;
        parent::__construct(
            $context,
            $itemRepository
        );
    }

    /**
     * @return void
     */
    public function execute()
    {
        $uploader = $this->uploaderFactory->create([
            'dir' => HelperData::ITEM_UPLOAD_DIR,
            'fileId' => 'file'
        ]);
        $data = $uploader->execute();
        $data->unsPath();
        return $this->resultFactory
            ->create(ResultFactory::TYPE_JSON)
            ->setData($data->toArray());
    }
}
