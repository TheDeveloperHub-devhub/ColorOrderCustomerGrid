<?php declare(strict_types=1);

namespace DeveloperHub\ColorOrderCustomerGrid\Block\Status\Grid\Column;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Column;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Sales\Model\ResourceModel\Order\Grid\CollectionFactory;
use DeveloperHub\ColorOrderCustomerGrid\Helper\Data;

class ColorCustomer extends Column
{
    protected $rowId = 0;

    /** @var Data */
    protected $helper;

    /** @var CollectionFactory */
    protected $collectionFactory;

    /**
     * @param Context $context
     * @param Data $helper
     * @param CollectionFactory $collectionFactory
     * @param array $data
     * @param JsonHelper|null $jsonHelper
     * @param DirectoryHelper|null $directoryHelper
     */
    public function __construct(
        Context $context,
        Data $helper,
        CollectionFactory $collectionFactory,
        array $data = [],
        ?JsonHelper $jsonHelper = null,
        ?DirectoryHelper $directoryHelper = null
    ) {
        parent::__construct($context, $data, $jsonHelper, $directoryHelper);
        $this->helper = $helper;
        $this->collectionFactory = $collectionFactory;
    }

    /** @return array */
    public function getFrameCallback()
    {
        return [$this, 'decorateAction'];
    }

    /**
     * @param $value
     * @param $row
     * @param $column
     * @param $isExport
     * @return string
     */
    public function decorateAction($value, $row, $column, $isExport)
    {
        $item = $this->collectionFactory->create()
            ->addFieldToSelect('status')
            ->addFieldToFilter('entity_id', $row->getEntityId())
            ->getFirstItem();

        $color = $this->helper->getStatusColor($item->getStatus());

        $cell = '<span data-init-row="' . $row->getId() . '"/><script>
            require(["jquery"],
                function($) {
                 $(".col-' . $column->getId() . ' [data-init-row=' . $row->getId() . ']")
                 .closest("tr")
                 .find("td")
                 .css("background","' . $color . '");
                }
            );
            </script>';

        return $cell;
    }
}
