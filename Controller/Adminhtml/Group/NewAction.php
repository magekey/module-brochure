<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Controller\Adminhtml\Group;

use MageKey\Brochure\Controller\Adminhtml\Group as AbstractController;

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
