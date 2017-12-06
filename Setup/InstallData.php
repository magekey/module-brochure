<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->getConnection()
            ->insertForce(
                $setup->getTable('mgk_brochure_group'),
                ['label' => 'Default']
            );
    }
}
