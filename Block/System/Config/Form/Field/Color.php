<?php declare(strict_types=1);

namespace DeveloperHub\ColorOrderCustomerGrid\Block\System\Config\Form\Field;

use Magento\Framework\Data\Form\Element\CollectionFactory;
use Magento\Framework\Data\Form\Element\Factory;
use Magento\Framework\Data\Form\Element\Text;
use Magento\Framework\Escaper;

class Color extends Text
{

    /**
     * @param Factory $factoryElement
     * @param CollectionFactory $factoryCollection
     * @param Escaper $escaper
     * @param $data
     */
    public function __construct(
        Factory $factoryElement,
        CollectionFactory $factoryCollection,
        Escaper $escaper,
        $data = []
    ) {
        parent::__construct($factoryElement, $factoryCollection, $escaper, $data);
        $this->setType('color');
    }

    /** @return mixed|string */
    public function getHtml()
    {
        if ($this->getRequired()) {
            $this->addClass('required-entry _required');
        }
        if ($this->_renderer) {
            $html = $this->_renderer->render($this);
        } else {
            $html = $this->getDefaultHtml();
        }

        return $html;
    }
}
