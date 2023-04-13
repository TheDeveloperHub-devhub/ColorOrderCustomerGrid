<?php declare(strict_types=1);

namespace DeveloperHub\ColorOrderCustomerGrid\Block\Status\Grid\Column;

use Magento\Backend\Block\Widget\Grid\Column;

class Color extends Column
{

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
        $cell = '';
        $state = $row->getState();
        if (!empty($state)) {
            if ($row->getColor()) {
                $cell = '<span style="width:40px;height:15px;margin-left:5px;display: inline-block;background: ' .
                    $row->getColor() . '">&nbsp;</span>';
            }
        }

        return $cell;
    }
}
