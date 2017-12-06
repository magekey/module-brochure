<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Controller\Adminhtml\Group;

use Magento\Framework\Controller\ResultFactory;
use MageKey\Brochure\Controller\Adminhtml\Group as AbstractController;

class Index extends AbstractController
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE);
        $resultPage->getConfig()->getTitle()->prepend(__('Groups'));

        return $resultPage;
    }
}
