<?php
declare(strict_types=1);

namespace Brimar\Shipping\Controller\Adminhtml\Actions;

use Brimar\Shipping\Model\BrimarShippingOptionsFactory;

class Delete extends \Brimar\Shipping\Controller\Adminhtml\Brimarshippingoptions
{
    protected $modelFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param BrimarShippingOptionsFactory $modelFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        BrimarShippingOptionsFactory $modelFactory
    ) {
        $this->modelFactory = $modelFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('brimar_shipping_options_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->modelFactory->create();
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Brimar Shipping Options.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['brimar_shipping_options_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Brimar Shipping Options to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}