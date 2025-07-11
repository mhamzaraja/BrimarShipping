<?php
declare(strict_types=1);

namespace Brimar\Shipping\Model;

use Brimar\Shipping\Api\Data\BrimarShippingOptionsInterface;
use Magento\Framework\Model\AbstractModel;

class BrimarShippingOptions extends AbstractModel implements BrimarShippingOptionsInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Brimar\Shipping\Model\ResourceModel\BrimarShippingOptions::class);
    }

    /**
     * @inheritDoc
     */
    public function getBrimarShippingOptionsId()
    {
        return $this->getData(self::BRIMAR_SHIPPING_OPTIONS_ID);
    }

    /**
     * @inheritDoc
     */
    public function setBrimarShippingOptionsId($brimarShippingOptionsId)
    {
        return $this->setData(self::BRIMAR_SHIPPING_OPTIONS_ID, $brimarShippingOptionsId);
    }

    /**
     * @inheritDoc
     */
    public function getCode()
    {
        return $this->getData(self::CODE);
    }

    /**
     * @inheritDoc
     */
    public function setCode($code)
    {
        return $this->setData(self::CODE, $code);
    }

    /**
     * @inheritDoc
     */
    public function getLabel()
    {
        return $this->getData(self::LABEL);
    }

    /**
     * @inheritDoc
     */
    public function setLabel($label)
    {
        return $this->setData(self::LABEL, $label);
    }

    /**
     * @inheritDoc
     */
    public function getPrice()
    {
        return $this->getData(self::PRICE);
    }

    /**
     * @inheritDoc
     */
    public function setPrice($price)
    {
        return $this->setData(self::PRICE, $price);
    }

    /**
     * @inheritDoc
     */
    public function getIsActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    /**
     * @inheritDoc
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}

