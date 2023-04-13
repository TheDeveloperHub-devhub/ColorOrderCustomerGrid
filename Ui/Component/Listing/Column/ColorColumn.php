<?php declare(strict_types=1);

namespace DeveloperHub\ColorOrderCustomerGrid\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class ColorColumn extends Column
{

    /**
     * Prepare Data Source
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $item[$this->getData('name')] = $this->prepareItem($item);
            }
        }

        return $dataSource;
    }

    /**
     * Get data
     * @param array $item
     * @return string
     */
    private function prepareItem(array $item)
    {
        $content = '';
        if ($item['color']) {
            $content = '<span style="width:60px;height:15px;margin-left:5px;display: inline-block;background: ' .
                $item['color'] . '">&nbsp;</span>';
        }
        return $content;
    }
}
