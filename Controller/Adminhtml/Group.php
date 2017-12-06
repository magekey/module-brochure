<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Controller\Adminhtml;

use Magento\Framework\Exception\LocalizedException;

use MageKey\Brochure\Api\Data\GroupInterface;
use MageKey\Brochure\Api\GroupRepositoryInterface;

abstract class Group extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'MageKey_Brochure::group';

    /**
     * UI Data
     */
    const UI_DATA_PERSISTOR = 'mgk_brochure_group';

    /**
     * @var GroupRepositoryInterface $groupRepository
     */
    protected $_groupRepository;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param GroupRepositoryInterface $groupRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        GroupRepositoryInterface $groupRepository
    ) {
        $this->_groupRepository = $groupRepository;
        parent::__construct($context);
    }

    /**
     * Load layout, set active menu and breadcrumbs
     *
     * @param string $pageTitle
     * @return $this
     */
    protected function _initAction($pageTitle)
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu(
            self::ADMIN_RESOURCE
        )->_addBreadcrumb(
            __($pageTitle),
            __($pageTitle)
        );
        $this->_view->getPage()->getConfig()->getTitle()->prepend($pageTitle);
        return $this;
    }

    /**
     * Load group
     *
     * @param int $groupId
     * @return GroupInterface
     * @throws LocalizedException
     */
    protected function loadGroup($groupId)
    {
        try {
            return $this->_groupRepository->getById($groupId);
        } catch (\Exception $e) {
            throw new LocalizedException(
                __('Group not found')
            );
        }
    }
}
