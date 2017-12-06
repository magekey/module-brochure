<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Controller\Adminhtml\Item;

use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;

use MageKey\Brochure\Controller\Adminhtml\Item as AbstractController;
use MageKey\Brochure\Api\ItemRepositoryInterface;
use MageKey\Brochure\Model\ResourceModel\Item\CollectionFactory as CollectionFactory;

class MassDelete extends AbstractController
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param ItemRepositoryInterface $itemRepository
     * @param CollectionFactory $collectionFactory
     * @param Filter $filter
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        ItemRepositoryInterface $itemRepository,
        CollectionFactory $collectionFactory,
        Filter $filter
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->filter = $filter;
        parent::__construct(
            $context,
            $itemRepository
        );
    }

    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();

        foreach ($collection as $item) {
            $this->_itemRepository->delete($item);
        }

        $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $collectionSize));

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
