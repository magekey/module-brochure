<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $connection = $setup->getConnection();

        /**
         * Create table 'mgk_brochure_group'
         */
        $table = $connection->newTable(
            $installer->getTable('mgk_brochure_group')
        )->addColumn(
            'group_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Group Id'
        )->addColumn(
            'label',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Group Label'
        )->setComment(
            'Brochure Groups'
        );
        $connection->createTable($table);

        /**
         * Create table 'mgk_brochure_item'
         */
        $table = $connection->newTable(
            $installer->getTable('mgk_brochure_item')
        )->addColumn(
            'item_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Item Id'
        )->addColumn(
            'title',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Item Title'
        )->addColumn(
            'image',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Item Image'
        )->addColumn(
            'file',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Item File'
        )->addColumn(
            'position',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'default' => '0'],
            'Position'
        )->addIndex(
            $installer->getIdxName('mgk_brochure_item', ['position']),
            ['position']
        )->setComment(
            'Brochure Items'
        );
        $connection->createTable($table);

        /**
         * Create table 'mgk_brochure_item_group'
         */
        $table = $connection->newTable(
            $installer->getTable('mgk_brochure_item_group')
        )->addColumn(
            'item_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'primary' => true],
            'Item Id'
        )->addColumn(
            'group_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'primary' => true],
            'Group ID'
        )->addForeignKey(
            $installer->getFkName('mgk_brochure_item_group', 'item_id', 'mgk_brochure_item', 'item_id'),
            'item_id',
            $installer->getTable('mgk_brochure_item'),
            'item_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName('mgk_brochure_item_group', 'group_id', 'mgk_brochure_group', 'group_id'),
            'group_id',
            $installer->getTable('mgk_brochure_group'),
            'group_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'Brochure Group Item Relation'
        );
        $connection->createTable($table);

        $installer->endSetup();
    }
}
