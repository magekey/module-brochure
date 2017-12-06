<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Controller\Adminhtml\Group;

use Magento\Framework\App\Request\DataPersistorInterface;

use MageKey\Brochure\Controller\Adminhtml\Group as AbstractController;
use MageKey\Brochure\Api\GroupRepositoryInterface;

class Edit extends AbstractController
{
    /**
     * @var DataPersistorInterface
     */
    protected $_dataPersistor;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param GroupRepositoryInterface $groupRepository
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        GroupRepositoryInterface $groupRepository,
        DataPersistorInterface $dataPersistor
    ) {
        $this->_dataPersistor = $dataPersistor;
        parent::__construct(
            $context,
            $groupRepository
        );
    }

    /**
     * @return void
     */
    public function execute()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $group = $this->loadGroup($id);
                if (!$this->_dataPersistor->get(self::UI_DATA_PERSISTOR)) {
                    $this->_dataPersistor->set(self::UI_DATA_PERSISTOR, $group->getData());
                }
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_redirect('*/*/index');
                return;
            }
        }

        $this->_initAction(isset($group) ? __('Group "%1"', $group->getLabel()) : __('New Group'));
        $this->_view->renderLayout();
    }
}
