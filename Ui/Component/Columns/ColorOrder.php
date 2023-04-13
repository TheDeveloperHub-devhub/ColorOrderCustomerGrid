<?php declare(strict_types=1);

namespace DeveloperHub\ColorOrderCustomerGrid\Ui\Component\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use DeveloperHub\ColorOrderCustomerGrid\Helper\Config as ConfigHelper;
use DeveloperHub\ColorOrderCustomerGrid\Helper\Data;

class ColorOrder extends Column
{
    /** @var Data */
    protected $helper;

    /** @var ConfigHelper */
    private $configHelper;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param Data $helper
     * @param ConfigHelper $configHelper
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        Data $helper,
        ConfigHelper $configHelper,
        array $components = [],
        array $data = []
    ) {
        parent::__construct(
            $context,
            $uiComponentFactory,
            $components,
            $data
        );
        $this->helper = $helper;
        $this->configHelper = $configHelper;
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if ($this->configHelper->getIsEnabledForOrdersGrid()) {
            if (isset($dataSource['data']['items'])) {
                foreach ($dataSource['data']['items'] as & $item) {
                    if (isset($item['status']) && !empty($item['status'])) {
                        $item['color_order'] = $this->getColor($item['status']);
                    }
                }
            }
        }
        return $dataSource;
    }

    /**
     * @param $status
     * @return mixed|string
     */
    protected function getColor($status)
    {
        return $this->helper->getStatusColor($status);
    }
}
