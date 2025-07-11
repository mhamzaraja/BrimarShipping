<?php
declare(strict_types=1);

namespace Brimar\Shipping\Controller\Shipping;

use Brimar\Shipping\Model\ResourceModel\BrimarShippingOptions\CollectionFactory;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;

class Options implements HttpGetActionInterface, CsrfAwareActionInterface
{
    private JsonFactory $resultJsonFactory;
    private CollectionFactory $collectionFactory;

    public function __construct(
        JsonFactory $resultJsonFactory,
        CollectionFactory $collectionFactory
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->collectionFactory = $collectionFactory;
    }

    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        return null;
    }

    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }

    public function execute(): Json
    {
        $result = $this->resultJsonFactory->create();
        
        try {
            $collection = $this->collectionFactory->create();
            $collection->addActiveFilter()->setSortOrder();

            $options = [];
            foreach ($collection as $option) {
                $options[] = [
                    'code' => $option->getCode(),
                    'label' => $option->getLabel(),
                    'price' => (float) $option->getPrice()
                ];
            }

            $result->setData([
                'success' => true,
                'options' => $options
            ]);
        } catch (\Exception $e) {
            $result->setData([
                'success' => false,
                'message' => $e->getMessage(),
                'options' => []
            ]);
        }

        return $result;
    }
}