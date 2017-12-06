<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Controller\Adminhtml\Group;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\Request\DataPersistorInterface;

use MageKey\Brochure\Controller\Adminhtml\Group as AbstractController;
use MageKey\Brochure\Api\Data\GroupInterfaceFactory;
use MageKey\Brochure\Api\GroupRepositoryInterface;

class Save extends AbstractController
{
    /**
     * @var GroupInterfaceFactory
     */
    protected $_groupFactory;

    /**
     * @var DataPersistorInterface
     */
    protected $_dataPersistor;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param GroupRepositoryInterface $groupRepository
     * @param GroupInterfaceFactory $groupFactory
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        GroupRepositoryInterface $groupRepository,
        GroupInterfaceFactory $groupFactory,
        DataPersistorInterface $dataPersistor
    ) {
        $this->_groupFactory = $groupFactory;
        $this->_dataPersistor = $dataPersistor;
        parent::__construct(
            $context,
            $groupRepository
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
                    $group = $this->loadGroup($id);
                } else {
                    $group = $this->_groupFactory->create();
                }
                $group->setData($data);
                $this->_groupRepository->save($group);
                $this->messageManager->addSuccess(__('You saved the group.'));
                $this->_dataPersistor->clear(static::UI_DATA_PERSISTOR);

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $group->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the group.'));
            }
            $this->_dataPersistor->set(static::UI_DATA_PERSISTOR, $data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
