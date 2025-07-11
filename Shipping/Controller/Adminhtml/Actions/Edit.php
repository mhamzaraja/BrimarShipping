<?php
declare(strict_types=1);

namespace Brimar\Shipping\Controller\Adminhtml\Actions;

use Brimar\Shipping\Model\BrimarShippingOptionsFactory;

class Edit extends \Brimar\Shipping\Controller\Adminhtml\Brimarshippingoptions
{
    protected $resultPageFactory;
    protected $modelFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param BrimarShippingOptionsFactory $modelFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        BrimarShippingOptionsFactory $modelFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->modelFactory = $modelFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('brimar_shipping_options_id');
        $model = $this->modelFactory->create();
        
        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Brimar Shipping Options no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('brimar_shipping_brimar_shipping_options', $model);
        
        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Shipping Options') : __('New Shipping Options'),
            $id ? __('Edit Shipping Options') : __('New Shipping Options')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Brimar Shipping Options'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Shipping Options') : __('New Shipping Options'));
        return $resultPage;
    }
}