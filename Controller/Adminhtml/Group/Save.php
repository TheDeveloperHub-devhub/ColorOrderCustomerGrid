<?php declare(strict_types=1);

namespace DeveloperHub\ColorOrderCustomerGrid\Controller\Adminhtml\Group;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Forward;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Customer\Api\Data\GroupExtensionInterfaceFactory;
use Magento\Customer\Api\Data\GroupInterfaceFactory;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Customer\Controller\Adminhtml\Group\Save as MagentoSave;
use Magento\Customer\Model\GroupFactory;
use Magento\Customer\Model\ResourceModel\Group;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Controller\Result\Redirect as ResultRedirect;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class Save extends MagentoSave
{
    /** @var GroupFactory */
    private $groupFactory;

    /** @var Group */
    private $groupResourceModel;

    /** @var GroupExtensionInterfaceFactory|mixed */
    private $groupExtensionInterfaceFactory;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param GroupRepositoryInterface $groupRepository
     * @param GroupInterfaceFactory $groupDataFactory
     * @param ForwardFactory $resultForwardFactory
     * @param PageFactory $resultPageFactory
     * @param DataObjectProcessor $dataObjectProcessor
     * @param GroupExtensionInterfaceFactory $groupExtensionInterfaceFactory
     * @param GroupFactory $groupFactory
     * @param Group $groupResourceModel
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        GroupRepositoryInterface $groupRepository,
        GroupInterfaceFactory $groupDataFactory,
        ForwardFactory $resultForwardFactory,
        PageFactory $resultPageFactory,
        DataObjectProcessor $dataObjectProcessor,
        GroupExtensionInterfaceFactory $groupExtensionInterfaceFactory,
        GroupFactory $groupFactory,
        Group $groupResourceModel
    ) {
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->groupExtensionInterfaceFactory = $groupExtensionInterfaceFactory
            ?: ObjectManager::getInstance()->get(GroupExtensionInterfaceFactory::class);
        $this->groupFactory = $groupFactory;
        $this->groupResourceModel = $groupResourceModel;
        parent::__construct($context, $coreRegistry, $groupRepository, $groupDataFactory, $resultForwardFactory, $resultPageFactory, $dataObjectProcessor, $groupExtensionInterfaceFactory);
    }

    /**
     * Create or save customer group.
     * @return Forward|Redirect|ResultRedirect
     */
    public function execute()
    {
        $taxClass = (int)$this->getRequest()->getParam('tax_class');
        /** @var \Magento\Customer\Api\Data\GroupInterface $customerGroup */
        $customerGroup = null;
        if ($taxClass) {
            $id = $this->getRequest()->getParam('id');
            $websitesToExclude = empty($this->getRequest()->getParam('customer_group_excluded_websites'))
                ? [] : $this->getRequest()->getParam('customer_group_excluded_websites');
            $resultRedirect = $this->resultRedirectFactory->create();
            try {
                $customerGroupCode = (string)$this->getRequest()->getParam('code');

                if ($id !== null) {
                    $customerGroup = $this->groupRepository->getById((int)$id);
                    $customerGroupCode = $customerGroupCode ?: $customerGroup->getCode();
                } else {
                    $customerGroup = $this->groupDataFactory->create();
                }

                $customerGroup->setCode(!empty($customerGroupCode) ? $customerGroupCode : null);
                $customerGroup->setTaxClassId($taxClass);
                if ($websitesToExclude !== null) {
                    $customerGroupExtensionAttributes = $this->groupExtensionInterfaceFactory->create();
                    $customerGroupExtensionAttributes->setExcludeWebsiteIds($websitesToExclude);
                    $customerGroup->setExtensionAttributes($customerGroupExtensionAttributes);
                }

                $customerGroup = $this->groupRepository->save($customerGroup);

                if ($customerGroup->getId() !== null) {
                    $groupModel = $this->groupFactory->create();
                    $this->groupResourceModel->load($groupModel, $customerGroup->getId());
                    $groupModel->setColor((string)$this->getRequest()->getParam('color'));
                    $this->groupResourceModel->save($groupModel);
                }

                $this->messageManager->addSuccessMessage(__('You saved the customer group.'));
                $resultRedirect->setPath('customer/group');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                if ($customerGroup != null) {
                    $this->storeCustomerGroupDataToSession(
                        $this->dataObjectProcessor->buildOutputDataArray(
                            $customerGroup,
                            \Magento\Customer\Api\Data\GroupInterface::class
                        )
                    );
                }
                $resultRedirect->setPath('customer/group/edit', ['id' => $id]);
            }
            return $resultRedirect;
        } else {
            return $this->resultForwardFactory->create()->forward('new');
        }
    }
}
