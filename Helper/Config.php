<?php declare(strict_types=1);

namespace DeveloperHub\ColorOrderCustomerGrid\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Config extends AbstractHelper
{
    /** Configuration Paths of Database */
    const ENABLED_ORDERS_GRID_CONFIG_PATH = 'developerhub_colorgrids/general/enable_orders_grid';
    const ENABLED_CUSTOMERS_GRID_CONFIG_PATH = 'developerhub_colorgrids/general/enable_customer_grid';

    /** @return bool */
    public function getIsEnabledForOrdersGrid()
    {
        return $this->scopeConfig->isSetFlag(self::ENABLED_ORDERS_GRID_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /** @return bool */
    public function getIsEnabledForCustomersGrid()
    {
        return $this->scopeConfig->isSetFlag(self::ENABLED_CUSTOMERS_GRID_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }
}
