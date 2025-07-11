<?php
declare(strict_types=1);

namespace Brimar\Shipping\Model\ResourceModel\BrimarShippingOptions;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'brimar_shipping_options_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \Brimar\Shipping\Model\BrimarShippingOptions::class,
            \Brimar\Shipping\Model\ResourceModel\BrimarShippingOptions::class
        );
    }

    /**
     * Add filter to get only active shipping options
     *
     * @return $this
     */
    public function addActiveFilter(): self
    {
        return $this->addFieldToFilter('is_active', 1);
    }

    /**
     * Set sort order for shipping options
     *
     * @param string $direction
     * @return $this
     */
    public function setSortOrder(string $direction = 'ASC'): self
    {
        return $this->setOrder('label', $direction);
    }
}