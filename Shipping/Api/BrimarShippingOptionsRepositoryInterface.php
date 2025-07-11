<?php
declare(strict_types=1);

namespace Brimar\Shipping\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface BrimarShippingOptionsRepositoryInterface
{

    /**
     * Save brimar_shipping_options
     * @param \Brimar\Shipping\Api\Data\BrimarShippingOptionsInterface $brimarShippingOptions
     * @return \Brimar\Shipping\Api\Data\BrimarShippingOptionsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Brimar\Shipping\Api\Data\BrimarShippingOptionsInterface $brimarShippingOptions
    );

    /**
     * Retrieve brimar_shipping_options
     * @param string $brimarShippingOptionsId
     * @return \Brimar\Shipping\Api\Data\BrimarShippingOptionsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($brimarShippingOptionsId);

    /**
     * Retrieve brimar_shipping_options matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Brimar\Shipping\Api\Data\BrimarShippingOptionsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete brimar_shipping_options
     * @param \Brimar\Shipping\Api\Data\BrimarShippingOptionsInterface $brimarShippingOptions
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Brimar\Shipping\Api\Data\BrimarShippingOptionsInterface $brimarShippingOptions
    );

    /**
     * Delete brimar_shipping_options by ID
     * @param string $brimarShippingOptionsId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($brimarShippingOptionsId);
}

