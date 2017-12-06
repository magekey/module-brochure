<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Controller\Adminhtml\Item;

use MageKey\Brochure\Controller\Adminhtml\Item as AbstractController;

class NewAction extends AbstractController
{
    /**
     * @return void
     */
    public function execute()
    {
        return $this->_forward('edit');
    }
}
