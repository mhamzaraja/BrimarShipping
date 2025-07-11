<?php
declare(strict_types=1);

namespace Brimar\Shipping\Model;

use Brimar\Shipping\Api\BrimarShippingOptionsRepositoryInterface;
use Brimar\Shipping\Api\Data\BrimarShippingOptionsInterface;
use Brimar\Shipping\Api\Data\BrimarShippingOptionsInterfaceFactory;
use Brimar\Shipping\Api\Data\BrimarShippingOptionsSearchResultsInterfaceFactory;
use Brimar\Shipping\Model\ResourceModel\BrimarShippingOptions as ResourceBrimarShippingOptions;
use Brimar\Shipping\Model\ResourceModel\BrimarShippingOptions\CollectionFactory as BrimarShippingOptionsCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class BrimarShippingOptionsRepository implements BrimarShippingOptionsRepositoryInterface
{

    /**
     * @var ResourceBrimarShippingOptions
     */
    protected $resource;

    /**
     * @var BrimarShippingOptionsInterfaceFactory
     */
    protected $brimarShippingOptionsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var BrimarShippingOptionsCollectionFactory
     */
    protected $brimarShippingOptionsCollectionFactory;

    /**
     * @var BrimarShippingOptions
     */
    protected $searchResultsFactory;


    /**
     * @param ResourceBrimarShippingOptions $resource
     * @param BrimarShippingOptionsInterfaceFactory $brimarShippingOptionsFactory
     * @param BrimarShippingOptionsCollectionFactory $brimarShippingOptionsCollectionFactory
     * @param BrimarShippingOptionsSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceBrimarShippingOptions $resource,
        BrimarShippingOptionsInterfaceFactory $brimarShippingOptionsFactory,
        BrimarShippingOptionsCollectionFactory $brimarShippingOptionsCollectionFactory,
        BrimarShippingOptionsSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->brimarShippingOptionsFactory = $brimarShippingOptionsFactory;
        $this->brimarShippingOptionsCollectionFactory = $brimarShippingOptionsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(
        BrimarShippingOptionsInterface $brimarShippingOptions
    ) {
        try {
            $this->resource->save($brimarShippingOptions);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the brimarShippingOptions: %1',
                $exception->getMessage()
            ));
        }
        return $brimarShippingOptions;
    }

    /**
     * @inheritDoc
     */
    public function get($brimarShippingOptionsId)
    {
        $brimarShippingOptions = $this->brimarShippingOptionsFactory->create();
        $this->resource->load($brimarShippingOptions, $brimarShippingOptionsId);
        if (!$brimarShippingOptions->getId()) {
            throw new NoSuchEntityException(__('brimar_shipping_options with id "%1" does not exist.', $brimarShippingOptionsId));
        }
        return $brimarShippingOptions;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->brimarShippingOptionsCollectionFactory->create();
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model;
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(
        BrimarShippingOptionsInterface $brimarShippingOptions
    ) {
        try {
            $brimarShippingOptionsModel = $this->brimarShippingOptionsFactory->create();
            $this->resource->load($brimarShippingOptionsModel, $brimarShippingOptions->getBrimarShippingOptionsId());
            $this->resource->delete($brimarShippingOptionsModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the brimar_shipping_options: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($brimarShippingOptionsId)
    {
        return $this->delete($this->get($brimarShippingOptionsId));
    }
}

