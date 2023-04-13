<?php declare(strict_types=1);

namespace DeveloperHub\ColorOrderCustomerGrid\Observer;

use Magento\Customer\Model\GroupFactory;
use Magento\Customer\Model\ResourceModel\Group;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use DeveloperHub\ColorOrderCustomerGrid\Block\System\Config\Form\Field\Color;
use DeveloperHub\ColorOrderCustomerGrid\Helper\Data;

class EditStatusFormBeforeHtml implements ObserverInterface
{
    /** @var Group */
    private $groupResourceModel;

    /** @var GroupFactory */
    private $groupFactory;

    /** @var RequestInterface */
    protected $request;

    /** @var Data */
    protected $helper;

    /**
     * @param RequestInterface $request
     * @param Data $helper
     * @param GroupFactory $groupFactory
     * @param Group $groupResourceModel
     */
    public function __construct(
        RequestInterface $request,
        Data $helper,
        GroupFactory $groupFactory,
        Group $groupResourceModel
    ) {
        $this->request = $request;
        $this->helper = $helper;
        $this->groupFactory = $groupFactory;
        $this->groupResourceModel = $groupResourceModel;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $block =  $observer->getEvent()->getBlock();
        if ($block->getId() == 'new_order_status') {
            /** @var \Magento\Framework\Data\Form $form */
            $form = $block->getForm();
            $fieldset = $form->addFieldset('base_fieldset_color', ['legend' => __('Customize')]);

            $fieldset->addType(
                'color',
                Color::class
            );

            $status = $this->request->getParam('status');

            $fieldset->addField(
                'color',
                'color',
                [
                    'name' => 'color',
                    'label' => __('Color'),
                    'required' => false,
                    'value'=>$this->helper->getStatusColor($status)
                ]
            );
        }
        if ((($block->getType() == "Magento\Customer\Block\Adminhtml\Group\Edit\Form")
            || ($block->getType() == "Magento\Customer\Block\Adminhtml\Group\Edit\Form\Interceptor"))
            && $block->getDestElementId() == 'edit_form') {
            $form = $block->getForm();
            $fieldset = $form->addFieldset('base_fieldset_color', ['legend' => __('Customize')]);

            $fieldset->addType(
                'color',
                Color::class
            );

            $groupModel = $this->groupFactory->create();
            $this->groupResourceModel->load($groupModel, $this->request->getParam('id'));
            $groupcode = $groupModel->getCustomerGroupCode();

            $fieldset->addField(
                'color',
                'color',
                [
                    'name' => 'color',
                    'label' => __('Color'),
                    'required' => false,
                    'value'=>$this->helper->getCustomerGroupColor($groupcode)
                ]
            );
        }
    }
}
