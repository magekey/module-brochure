<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Controller\Adminhtml\Item;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\Request\DataPersistorInterface;

use MageKey\Brochure\Controller\Adminhtml\Item as AbstractController;
use MageKey\Brochure\Api\Data\ItemInterfaceFactory;
use MageKey\Brochure\Api\ItemRepositoryInterface;

class Save extends AbstractController
{
    /**
     * @var ItemInterfaceFactory
     */
    protected $_itemFactory;

    /**
     * @var DataPersistorInterface
     */
    protected $_dataPersistor;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param ItemRepositoryInterface $itemRepository
     * @param ItemInterfaceFactory $itemFactory
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        ItemRepositoryInterface $itemRepository,
        ItemInterfaceFactory $itemFactory,
        DataPersistorInterface $dataPersistor
    ) {
        $this->_itemFactory = $itemFactory;
        $this->_dataPersistor = $dataPersistor;
        parent::__construct(
            $context,
            $itemRepository
        );
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            try {
                if ($id = $this->getRequest()->getParam('id')) {
                    $item = $this->loadItem($id);
                } else {
                    $item = $this->_itemFactory->create();
                }
                $item->setData($data);
                $this->_itemRepository->save($item);
                $this->messageManager->addSuccess(__('You saved the item.'));
                $this->_dataPersistor->clear(static::UI_DATA_PERSISTOR);

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $item->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the item.'));
            }
            $this->_dataPersistor->set(static::UI_DATA_PERSISTOR, $data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
