<?php
declare(strict_types=1);

namespace Brimar\Shipping\Api\Data;

interface BrimarShippingOptionsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get brimar_shipping_options list.
     * @return \Brimar\Shipping\Api\Data\BrimarShippingOptionsInterface[]
     */
    public function getItems();

    /**
     * Set code list.
     * @param \Brimar\Shipping\Api\Data\BrimarShippingOptionsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

