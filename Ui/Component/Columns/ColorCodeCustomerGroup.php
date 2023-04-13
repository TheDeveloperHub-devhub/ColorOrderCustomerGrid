<?php declare(strict_types=1);

namespace DeveloperHub\ColorOrderCustomerGrid\Ui\Component\Columns;

use Magento\Customer\Model\Group as groupModel;
use Magento\Customer\Model\ResourceModel\Group;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use DeveloperHub\ColorOrderCustomerGrid\Helper\Config as ConfigHelper;
use DeveloperHub\ColorOrderCustomerGrid\Helper\Data;

class ColorCodeCustomerGroup extends Column
{
    /** @var groupModel */
    private $groupModel;

    /** @var Group */
    protected $groupResourceModel;

    /** @var Data */
    protected $helper;

    /** @var ConfigHelper */
    private $configHelper;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param Group $groupResourceModel
     * @param groupModel $groupModel
     * @param Data $helper
     * @param ConfigHelper $configHelper
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface   $context,
        UiComponentFactory $uiComponentFactory,
        Group              $groupResourceModel,
        groupModel         $groupModel,
        Data               $helper,
        ConfigHelper       $configHelper,
        array              $components = [],
        array              $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->helper = $helper;
        $this->groupModel = $groupModel;
        $this->groupResourceModel = $groupResourceModel;
        $this->configHelper = $configHelper;
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if ($this->configHelper->getIsEnabledForCustomersGrid()) {
            if (isset($dataSource['data']['items'])) {
                foreach ($dataSource['data']['items'] as & $item) {
                    if (isset($item['group_id']) && !empty($item['group_id'])) {
                        $item['color_order'] = $this->getCustomerGroupCode($item['group_id']);
                    }
                }
            }
        }
        return $dataSource;
    }

    /**
     * @param $groupCode
     * @return mixed|string
     */
    protected function getColor($groupCode)
    {
        return $this->helper->getCustomerGroupColor($groupCode);
    }

    /**
     * @param $customerGroupId
     * @return mixed
     */
    protected function getCustomerGroupCode($customerGroupId)
    {
        $this->groupResourceModel->load($this->groupModel, $customerGroupId);
        return $this->groupModel->getColor();
    }
}
