<?php
declare(strict_types=1);

namespace Brimar\Shipping\Controller\Shipping;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;

class Save implements HttpPostActionInterface, CsrfAwareActionInterface
{
    private JsonFactory $resultJsonFactory;
    private Session $checkoutSession;
    private RequestInterface $request;

    public function __construct(
        JsonFactory $resultJsonFactory,
        Session $checkoutSession,
        RequestInterface $request
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->checkoutSession = $checkoutSession;
        $this->request = $request;
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
            $option = $this->request->getParam('option');
            $quote = $this->checkoutSession->getQuote();
            
            if ($quote && $option) {
                // Parse option JSON
                $selectedOption = json_decode($option, true);
                
                if ($selectedOption) {
                    // Save option to quote
                    $quote->setBrimarShippingOptions($option);
                    
                    // Save option price
                    $optionPrice = (float) $selectedOption['price'];
                    $quote->setBrimarShippingFee($optionPrice);
                    
                    // Get shipping address
                    $shippingAddress = $quote->getShippingAddress();

                    $result->setData([
                        'message' => $shippingAddress->getShippingMethod(),
                    ]);
                             
                    // Clear totals collection flag
                    $quote->setTotalsCollectedFlag(false);
                    
                    // Force complete totals recalculation
                    $quote->collectTotals();
                    
                    // Save quote
                    $quote->save();
                    
                    // Get updated totals after save
                    $updatedShippingAmount = $shippingAddress->getShippingAmount();
                    $updatedGrandTotal = $quote->getGrandTotal();
                    
                    $result->setData([
                        'success' => true,
                        'message' => 'Option saved and totals updated',
                        'selected_option' => $selectedOption,
                        'shipping_fee' => $optionPrice,
                        'debug_info' => [
                            'quote_id' => $quote->getId(),
                            'shipping_amount_set' => $optionPrice,
                            'shipping_amount_after_save' => $updatedShippingAmount,
                            'grand_total' => $updatedGrandTotal,
                            'shipping_method' => $shippingAddress->getShippingMethod()
                        ]
                    ]);
                } else {
                    $result->setData([
                        'success' => false,
                        'message' => 'Invalid option data'
                    ]);
                }
            } else {
                $result->setData([
                    'success' => false,
                    'message' => 'Invalid request'
                ]);
            }
        } catch (\Exception $e) {
            $result->setData([
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return $result;
    }
}