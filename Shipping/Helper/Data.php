<?php
declare(strict_types=1);

namespace Brimar\Shipping\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Pricing\Helper\Data as PricingHelper;

class Data extends AbstractHelper
{
    private PricingHelper $pricingHelper;

    public function __construct(
        Context $context,
        PricingHelper $pricingHelper
    ) {
        parent::__construct($context);
        $this->pricingHelper = $pricingHelper;
    }

    public function formatPrice(float $price): string
    {
        return $this->pricingHelper->currency($price, true, false);
    }

    public function isBrimarShipping(string $shippingMethod): bool
    {
        return $shippingMethod === 'brimar_brimar';
    }

    public function decodeShippingOptions(?string $optionsJson): array
    {
        if (!$optionsJson) {
            return [];
        }

        $options = json_decode($optionsJson, true);
        return is_array($options) ? $options : [];
    }
}