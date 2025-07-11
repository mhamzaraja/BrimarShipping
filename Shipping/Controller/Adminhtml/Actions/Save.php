<?php
declare(strict_types=1);

namespace Brimar\Shipping\Controller\Adminhtml\Actions;

use Magento\Framework\Exception\LocalizedException;
use Brimar\Shipping\Model\BrimarShippingOptionsFactory;

class Save extends \Magento\Backend\App\Action
{
    protected $dataPersistor;
    protected $modelFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param BrimarShippingOptionsFactory $modelFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        BrimarShippingOptionsFactory $modelFactory
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->modelFactory = $modelFactory;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('brimar_shipping_options_id');
        
            $model = $this->modelFactory->create()->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Brimar Shipping Options no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
        
            $model->setData($data);
        
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Brimar Shipping Options.'));
                $this->dataPersistor->clear('brimar_shipping_brimar_shipping_options');
        
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['brimar_shipping_options_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Brimar Shipping Options.'));
            }
        
            $this->dataPersistor->set('brimar_shipping_brimar_shipping_options', $data);
            return $resultRedirect->setPath('*/*/edit', ['brimar_shipping_options_id' => $this->getRequest()->getParam('brimar_shipping_options_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}