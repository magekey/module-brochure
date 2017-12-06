<?php
/**
 * Copyright MageKey. All rights reserved.
 */
namespace MageKey\Brochure\Block\Adminhtml\Widget;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Widget\Block\Adminhtml\Widget\Chooser as BlockWidgetChooser;

use MageKey\Brochure\Model\GroupFactory as ModelFactory;
use MageKey\Brochure\Model\ResourceModel\Group\CollectionFactory;

class Chooser extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * Url path
     */
    const URL_PATH_GRID = 'mgk_brochure/group/chooser';

    /**
     * Fields
     */
    const FIELD_ID = 'group_id';
    const FIELD_NAME = 'label';
    const LABEL_NAME = 'Label';

    /**
     * @var ModelFactory
     */
    protected $modelFactory;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param ModelFactory $modelFactory
     * @param CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        ModelFactory $modelFactory,
        CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->modelFactory = $modelFactory;
        $this->collectionFactory = $collectionFactory;
        parent::__construct(
            $context,
            $backendHelper,
            $data
        );
    }

    /**
     * Construction, prepare grid params
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setDefaultSort(static::FIELD_ID);
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        $this->setDefaultFilter(['chooser_id' => '1']);
    }

    /**
     * Prepare chooser element HTML
     *
     * @param AbstractElement $element
     * @return AbstractElement
     */
    public function prepareElementHtml(AbstractElement $element)
    {
        $uniqId = $this->mathRandom->getUniqueHash($element->getId());
        $sourceUrl = $this->getUrl(static::URL_PATH_GRID, ['uniq_id' => $uniqId]);

        $chooser = $this->getLayout()
            ->createBlock(BlockWidgetChooser::class)
            ->setElement($element)
            ->setConfig($this->getConfig())
            ->setFieldsetId($this->getFieldsetId())
            ->setSourceUrl($sourceUrl)
            ->setUniqId($uniqId);

        if ($element->getValue()) {
            $block = $this->modelFactory->create()->load($element->getValue());
            if ($block->getId()) {
                $chooser->setLabel($this->escapeHtml($block->getName()));
            }
        }

        $element->setData('after_element_html', $chooser->toHtml());
        return $element;
    }

    /**
     * Grid Row JS Callback
     *
     * @return string
     */
    public function getRowClickCallback()
    {
        $chooserJsObject = $this->getId();
        $js = '
            function (grid, event) {
                var trElement = Event.findElement(event, "tr");
                var blockId = trElement.down("td").innerHTML.replace(/^\s+|\s+$/g,"");
                var blockTitle = trElement.down("td").next().innerHTML; '
                . $chooserJsObject . '.setElementValue(blockId); '
                . $chooserJsObject . '.setElementLabel(blockTitle); '
                . $chooserJsObject . '.close();
            }';
        return $js;
    }

    /**
     * Prepare blocks collection
     *
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    protected function _prepareCollection()
    {
        $collection = $this->collectionFactory->create();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare columns for grid
     *
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'chooser_id',
            [
                'header' => __('ID'),
                'align' => 'right',
                'index' => static::FIELD_ID,
                'width' => 50
            ]
        );
        $this->addColumn(
            'chooser_name',
            [
                'header' => __(static::LABEL_NAME),
                'align' => 'left',
                'index' => static::FIELD_NAME
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * Get grid url
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl(static::URL_PATH_GRID, ['_current' => true]);
    }
}
