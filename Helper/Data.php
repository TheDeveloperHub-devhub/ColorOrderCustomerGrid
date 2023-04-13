<?php declare(strict_types=1);

namespace DeveloperHub\ColorOrderCustomerGrid\Helper;

use Magento\Customer\Model\Group;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Sales\Model\Order\Status;
use Magento\Sales\Model\ResourceModel\Order\Status\Collection as SalesCollection;
use Magento\Customer\Model\ResourceModel\Group\Collection as CustomerGroupCollection;

class Data extends AbstractHelper
{
    /** @var SalesCollection */
    protected $salesCollection;

    /** @var CustomerGroupCollection */
    protected $customerGroupCollection;

    /** @var mixed */
    protected $statusColor;

    protected $customerGroupColor;

    /**
     * @param Context $context
     * @param SalesCollection $salesCollection
     * @param CustomerGroupCollection $customerGroupCollection
     */
    public function __construct(
        Context $context,
        SalesCollection $salesCollection,
        CustomerGroupCollection $customerGroupCollection
    ) {
        parent::__construct($context);
        $this->salesCollection = $salesCollection;
        $this->customerGroupCollection = $customerGroupCollection;
    }

    /**
     * @param $status
     * @return mixed|string
     */
    public function getStatusColor($status)
    {
        $statues = $this->getColorStatuses();
        return (isset($statues[$status])) ? $statues[$status] : '';
    }

    /** @return mixed */
    public function getColorStatuses()
    {
        if (!isset($this->statusColor)) {
            /** @var Status $item */
            foreach ($this->salesCollection->getItems() as $item) {
                $this->statusColor[$item->getStatus()] = $item->getData('color');
            }
        }
        return $this->statusColor;
    }

    /**
     * @param $groupCode
     * @return mixed|string
     */
    public function getCustomerGroupColor($groupCode)
    {
        $customerGroupColors = $this->getCustomerGroupColors();
        return (isset($customerGroupColors[$groupCode])) ? $customerGroupColors[$groupCode] : '';
    }

    /** @return mixed */
    public function getCustomerGroupColors()
    {
        if (!isset($this->customerGroupColor)) {
            /** @var Group $item */
            foreach ($this->customerGroupCollection->getItems() as $item) {
                $this->customerGroupColor[$item->getCustomerGroupCode()] = $item->getData('color');
            }
        }
        return $this->customerGroupColor;
    }
}
