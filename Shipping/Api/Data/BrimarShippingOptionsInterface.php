<?php
declare(strict_types=1);

namespace Brimar\Shipping\Api\Data;

interface BrimarShippingOptionsInterface
{

    const UPDATED_AT = 'updated_at';
    const CODE = 'code';
    const PRICE = 'price';
    const CREATED_AT = 'created_at';
    const BRIMAR_SHIPPING_OPTIONS_ID = 'brimar_shipping_options_id';
    const LABEL = 'label';
    const IS_ACTIVE = 'is_active';

    /**
     * Get brimar_shipping_options_id
     * @return string|null
     */
    public function getBrimarShippingOptionsId();

    /**
     * Set brimar_shipping_options_id
     * @param string $brimarShippingOptionsId
     * @return \Brimar\Shipping\BrimarShippingOptions\Api\Data\BrimarShippingOptionsInterface
     */
    public function setBrimarShippingOptionsId($brimarShippingOptionsId);

    /**
     * Get code
     * @return string|null
     */
    public function getCode();

    /**
     * Set code
     * @param string $code
     * @return \Brimar\Shipping\BrimarShippingOptions\Api\Data\BrimarShippingOptionsInterface
     */
    public function setCode($code);

    /**
     * Get label
     * @return string|null
     */
    public function getLabel();

    /**
     * Set label
     * @param string $label
     * @return \Brimar\Shipping\BrimarShippingOptions\Api\Data\BrimarShippingOptionsInterface
     */
    public function setLabel($label);

    /**
     * Get price
     * @return string|null
     */
    public function getPrice();

    /**
     * Set price
     * @param string $price
     * @return \Brimar\Shipping\BrimarShippingOptions\Api\Data\BrimarShippingOptionsInterface
     */
    public function setPrice($price);

    /**
     * Get is_active
     * @return string|null
     */
    public function getIsActive();

    /**
     * Set is_active
     * @param string $isActive
     * @return \Brimar\Shipping\BrimarShippingOptions\Api\Data\BrimarShippingOptionsInterface
     */
    public function setIsActive($isActive);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Brimar\Shipping\BrimarShippingOptions\Api\Data\BrimarShippingOptionsInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get updated_at
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated_at
     * @param string $updatedAt
     * @return \Brimar\Shipping\BrimarShippingOptions\Api\Data\BrimarShippingOptionsInterface
     */
    public function setUpdatedAt($updatedAt);
}

