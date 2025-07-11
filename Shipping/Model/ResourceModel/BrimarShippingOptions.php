<?php
declare(strict_types=1);

namespace Brimar\Shipping\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class BrimarShippingOptions extends AbstractDb
{

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('brimar_shipping_options', 'brimar_shipping_options_id');
    }
}

