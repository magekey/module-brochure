<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Controller\Adminhtml\Item;

use Magento\Framework\App\Request\DataPersistorInterface;

use MageKey\Brochure\Controller\Adminhtml\Item as AbstractController;
use MageKey\Brochure\Api\ItemRepositoryInterface;

class Edit extends AbstractController
{
    /**
     * @var DataPersistorInterface
     */
    protected $_dataPersistor;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param ItemRepositoryInterface $itemRepository
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        ItemRepositoryInterface $itemRepository,
        DataPersistorInterface $dataPersistor
    ) {
        $this->_dataPersistor = $dataPersistor;
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
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $item = $this->loadItem($id);
                if (!$this->_dataPersistor->get(self::UI_DATA_PERSISTOR)) {
                    $this->_dataPersistor->set(self::UI_DATA_PERSISTOR, $item->getData());
                }
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_redirect('*/*/index');
                return;
            }
        }

        $this->_initAction(isset($item) ? __('Item "%1"', $item->getTitle()) : __('New Item'));
        $this->_view->renderLayout();
    }
}
