<?php
declare(strict_types=1);

namespace Brimar\Shipping\Plugin\Quote\Model;

use Magento\Checkout\Model\Session;
use Magento\Quote\Api\Data\ShippingMethodInterface;

class ShippingMethodManagement
{
    private Session $checkoutSession;

    public function __construct(Session $checkoutSession)
    {
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * Add additional fee to Brimar shipping method
     */
    public function afterEstimateByExtendedAddress($subject, $result): array
    {
        return $this->addBrimarFeeToMethods($result);
    }

    /**
     * Add additional fee to Brimar shipping method
     */
    public function afterEstimateByAddressId($subject, $result): array
    {
        return $this->addBrimarFeeToMethods($result);
    }

    private function addBrimarFeeToMethods(array $methods): array
    {
        try {
            $quote = $this->checkoutSession->getQuote();
            $brimarOptions = $quote->getBrimarShippingOptions();
            $brimarFee = $quote->getBrimarShippingFee();

            if (!$brimarOptions || !$brimarFee) {
                return $methods;
            }

            // Parse selected option
            $selectedOption = json_decode($brimarOptions, true);
            if (!$selectedOption) {
                return $methods;
            }

            foreach ($methods as $method) {
                if ($method instanceof ShippingMethodInterface && 
                    $method->getCarrierCode() === 'brimar') {
                    
                    // Set the price to the selected option price
                    $method->setAmount($brimarFee);
                    $method->setPriceExclTax($brimarFee);
                    $method->setPriceInclTax($brimarFee);
                    
                    // Update method title to show selected option
                    $newTitle = $selectedOption['label'];
                    $method->setMethodTitle($newTitle);
                }
            }

            return $methods;
        } catch (\Exception $e) {
            return $methods;
        }
    }
}