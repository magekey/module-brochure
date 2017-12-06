<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Controller\Adminhtml;

use Magento\Framework\Exception\LocalizedException;

use MageKey\Brochure\Api\Data\ItemInterface;
use MageKey\Brochure\Api\ItemRepositoryInterface;

abstract class Item extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'MageKey_Brochure::item';

    /**
     * UI Data
     */
    const UI_DATA_PERSISTOR = 'mgk_brochure_item';

    /**
     * @var ItemRepositoryInterface $itemRepository
     */
    protected $_itemRepository;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param ItemRepositoryInterface $itemRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        ItemRepositoryInterface $itemRepository
    ) {
        $this->_itemRepository = $itemRepository;
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
     * Load item
     *
     * @param int $itemId
     * @return ItemInterface
     * @throws LocalizedException
     */
    protected function loadItem($itemId)
    {
        try {
            return $this->_itemRepository->getById($itemId);
        } catch (\Exception $e) {
            throw new LocalizedException(
                __('Item not found')
            );
        }
    }
}
