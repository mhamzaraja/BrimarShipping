<?php
declare(strict_types=1);

namespace Brimar\Shipping\Observer;

use Brimar\Shipping\Helper\Data as BrimarHelper;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class SaveShippingOptions implements ObserverInterface
{
    private BrimarHelper $brimarHelper;

    public function __construct(BrimarHelper $brimarHelper)
    {
        $this->brimarHelper = $brimarHelper;
    }

    public function execute(Observer $observer): void
    {
        $quote = $observer->getQuote();
        $order = $observer->getOrder();

        $shippingMethod = $quote->getShippingAddress()->getShippingMethod();
        
        if ($this->brimarHelper->isBrimarShipping($shippingMethod)) {
            $brimarOptions = $quote->getBrimarShippingOptions();
            $brimarFee = $quote->getBrimarShippingFee();

            if ($brimarOptions) {
                $order->setBrimarShippingOptions($brimarOptions);
            }
            if ($brimarFee) {
                $order->setBrimarShippingFee($brimarFee);
            }
        }
    }
}